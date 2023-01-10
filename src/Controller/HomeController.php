<?php

namespace App\Controller;

use App\Form\Service\NewsletterService as ServiceNewsletterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(
		Request $request,
		ServiceNewsletterService $newsletterService,
	): Response
    {
		$newsletterForm = $newsletterService->createForm();
		$newsletterForm->handleRequest($request);
		if($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
			$newsletterService->handleForm($newsletterForm->getData());
			$this->redirectToRoute('home');
		}
		
        return $this->render('home/home.' . $request->getLocale() . '.html.twig', [
            'page' => 'home',
			'newsletterForm' => $newsletterForm->createView()
        ]);
    }
}
