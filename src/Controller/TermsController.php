<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TermsController extends AbstractController
{
	#[Route('/privacy', name: 'privacy')]
	public function privacy(): Response
	{
		return $this->render('terms/privacy.html.twig', [
			'page' => 'privacy',
		]);
	}
}