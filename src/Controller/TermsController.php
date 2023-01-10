<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TermsController extends AbstractController
{
	#[Route('/privacy', name: 'privacy', options: ['sitemap' => false])]
	public function privacy(string $_locale): Response
	{
		return $this->render("terms/privacy.$_locale.html.twig", [
			'page' => 'privacy',
		]);
	}
}