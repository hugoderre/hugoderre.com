<?php

namespace App\Controller;

use App\Form\Type\LoginType;
use App\Form\Type\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'security_login', priority:1)]
    public function login(AuthenticationUtils $utils): Response
    {
        $form = $this->createForm(LoginType::class);
     
        return $this->render('security/login.html.twig', [
            'formView' => $form->createView(),
            'error' => $utils->getLastAuthenticationError(),
            'page' => 'security-login',
        ]);
    }

    #[Route('/logout', name: 'security_logout', priority:1)]
    public function logout()
    {
    }

    #[Route('/registration', name: 'security_registration')]
    public function registration(AuthenticationUtils $utils): Response
    {
        $form = $this->createForm(RegistrationType::class);
     
        return $this->render('security/registration.html.twig', [
            'formView' => $form->createView(),
            'error' => $utils->getLastAuthenticationError(),
            'page' => 'security-login',
        ]);
    }
}
