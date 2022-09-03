<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\Email\NewsletterType;
use MailchimpMarketing\ApiClient;
use MailchimpMarketing\ApiException;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StaticPageController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(
		Request $request, 
		ValidatorInterface $validator,
		string $MAILCHIMP_KEY,
		string $MAILCHIMP_SERVER_PREFIX,
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
			$mailchimp = new ApiClient();
			$mailchimp->setConfig([
				'apiKey' => $MAILCHIMP_KEY,
				'server' => $MAILCHIMP_SERVER_PREFIX,
			]);

			$list_id = $MAILCHIMP_LIST_ID;

			try {
				$response = $mailchimp->lists->addListMember($list_id, [
					"email_address" => $newsletterFormData['email'],
					"status" => "subscribed",
				]);
				dump($response);
				$this->addFlash('success', 'Merci ! Votre inscription à la newsletter a bien été prise en compte.');
			} catch (ApiException $e) {
				dump($e->getMessage());
				$this->addFlash('error', 'Oups ! Une erreur est survenue lors de votre inscription à la newsletter.');
			}

			return $this->redirectToRoute('home');
		}
		
        return $this->render('static-pages/home.html.twig', [
            'page' => 'home',
			'newsletterForm' => $newsletterForm->createView()
        ]);
    }

	#[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('static-pages/contact.html.twig', [
            'page' => 'contact'
        ]);
    }
}
