<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticPageController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('static-pages/home.html.twig', [
            'page' => 'home'
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
