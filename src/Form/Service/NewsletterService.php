<?php

namespace App\Form\Service;

use App\Form\Type\Email\NewsletterType;
use App\Integration\MailchimpService;
use Symfony\Component\Form\FormFactoryInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NewsletterService
{
	public function __construct(
		private FormFactoryInterface $formFactory,
		private MailchimpService $mailchimpService, 
		private ValidatorInterface $validator, 
		private string $MAILCHIMP_LIST_ID
	) {}

	public function createForm()
	{
		return $this->formFactory->create(NewsletterType::class);;
	}

	public function handleForm($formData, Request $request)
	{
		$session = $request->getSession();
		$emailConstraint = new EmailConstraint();
		
		$errors = $this->validator->validate(
			$formData['email'],
			$emailConstraint
		);
		
		if($errors->count() > 0) {
			$session->getBag('flashes')->add('error', 'L\'adresse email n\'est pas valide.');
			return;
		}

		try {
			$this->mailchimpService->addListMember($this->MAILCHIMP_LIST_ID, [
				'email_address' => $formData['email'],
				'status' => 'subscribed',
			]);
			$session->getBag('flashes')->add('success', 'Merci ! Votre inscription à la newsletter a bien été prise en compte.');
		} catch (ClientException $e) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			$decoded = json_decode($responseBodyAsString);
			if($decoded->status == 400) {
				$session->getBag('flashes')->add('error', 'Vous êtes déjà inscrit à la newsletter !');
			} else {
				$session->getBag('flashes')->add('error', 'Oups ! Une erreur est survenue lors de votre inscription à la newsletter.');
			}
		} catch (ConnectException $e) {
			$session->getBag('flashes')->add('error', 'L\'inscription à la newsletter rencontre un petit problême... Veuillez réessayer plus tard.');
		}
	}
}