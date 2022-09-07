<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\Email\NewsletterType;
use App\Integration\MailchimpService;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use MailchimpMarketing\ApiException;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(
		Request $request, 
		ValidatorInterface $validator,
		MailchimpService $mailchimpService,
		string $MAILCHIMP_LIST_ID
	): Response
    {
		$newsletterForm = $this->createForm(NewsletterType::class);
		$newsletterForm->handleRequest($request);
		if($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
			$newsletterFormData = $newsletterForm->getData();

			$emailConstraint = new EmailConstraint();
			
			$errors = $validator->validate(
				$newsletterFormData['email'],
				$emailConstraint
			);
			
			if($errors->count() > 0) {
				$this->addFlash('error', 'L\'adresse email n\'est pas valide.');
				return $this->redirectToRoute('home');
			}

			// add email to mailchimp audience
			try {
				$mailchimpService->addListMember($MAILCHIMP_LIST_ID, [
					'email_address' => $newsletterFormData['email'],
					'status' => 'subscribed',
				]);
				$this->addFlash('success', 'Merci ! Votre inscription à la newsletter a bien été prise en compte.');
			} catch (ClientException $e) {
				$response = $e->getResponse();
				$responseBodyAsString = $response->getBody()->getContents();
				$decoded = json_decode($responseBodyAsString);
				if($decoded->status == 400) {
					$this->addFlash('error', 'Vous êtes déjà inscrit à la newsletter !');
				} else {
					$this->addFlash('error', 'Oups ! Une erreur est survenue lors de votre inscription à la newsletter.');
				}
			} catch (ConnectException $e) {
				$this->addFlash('error', 'L\'inscription à la newsletter rencontre un petit problême... Veuillez réessayer plus tard.');
			}

			return $this->redirectToRoute('home');
		}
		
        return $this->render('home/home.html.twig', [
            'page' => 'home',
			'newsletterForm' => $newsletterForm->createView()
        ]);
    }
}
