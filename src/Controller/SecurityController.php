<?php

namespace App\Controller;

use App\Form\Type\Security\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login-{psw}', name: 'login', priority:1)]
    public function login(AuthenticationUtils $utils, string $psw, string $LOGIN_PATH_SECURITY_CHECK): Response
    {
		if($psw !== $LOGIN_PATH_SECURITY_CHECK) {
			throw $this->createNotFoundException('Page not found');
		}

        $form = $this->createForm(LoginType::class);

        if($lastAuthenticationError = $utils->getLastAuthenticationError()){
            $this->addFlash('error', $lastAuthenticationError->getMessage());
        }
     
        return $this->render('security/login.html.twig', [
            'formView' => $form->createView(),
            'page' => 'security-login',
        ]);
    }

    #[Route('/logout', name: 'logout', priority:1)]
    public function logout() {}
}
