<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KisouijController extends AbstractController
{
    #[Route('/kisouij', name: 'kisouij')]
    public function index(): Response
    {
        return $this->render('kisouij/index.html.twig', [
            'page' => 'kisouij'
        ]);
    }
}
