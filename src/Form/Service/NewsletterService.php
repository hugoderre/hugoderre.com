<?php

namespace App\Form\Service;

use App\Form\Type\Email\NewsletterType;
use App\Integration\MailchimpService;
use Symfony\Component\Form\FormFactoryInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NewsletterService
{
	private $session;

	public function __construct(
		ContainerInterface $container,
		private FormFactoryInterface $formFactory,
		private MailchimpService $mailchimpService, 
		private ValidatorInterface $validator, 
		private string $MAILCHIMP_LIST_ID
	)
	{
		$this->session = $container->get('session');
		$this->mailchimpService = $mailchimpService;
		$this->validator = $validator;
		$this->MAILCHIMP_LIST_ID = $MAILCHIMP_LIST_ID;
	}

	public function createForm()
	{
		return $this->formFactory->create(NewsletterType::class);;
	}

	public function handleForm($formData)
	{
		$emailConstraint = new EmailConstraint();
		
		$errors = $this->validator->validate(
			$formData['email'],
			$emailConstraint
		);
		
		if($errors->count() > 0) {
			$this->session->getFlashBag()->add('error', 'L\'adresse email n\'est pas valide.');
			return;
		}

		try {
			$this->mailchimpService->addListMember($this->MAILCHIMP_LIST_ID, [
				'email_address' => $formData['email'],
				'status' => 'subscribed',
			]);
			$this->session->getFlashBag()->add('success', 'Merci ! Votre inscription à la newsletter a bien été prise en compte.');
		} catch (ClientException $e) {
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			$decoded = json_decode($responseBodyAsString);
			if($decoded->status == 400) {
				$this->session->getFlashBag()->add('error', 'Vous êtes déjà inscrit à la newsletter !');
			} else {
				$this->session->getFlashBag()->add('error', 'Oups ! Une erreur est survenue lors de votre inscription à la newsletter.');
			}
		} catch (ConnectException $e) {
			$this->session->getFlashBag()->add('error', 'L\'inscription à la newsletter rencontre un petit problême... Veuillez réessayer plus tard.');
		}
	}
}