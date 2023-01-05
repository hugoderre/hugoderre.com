<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class LocaleController extends AbstractController
{
	#[Route('/change_locale/{locale}', name: 'change_locale', requirements: ['locale' => '%app_locales%'])]
	public function changeLocale($locale, Request $request, RouterInterface $router): Response
	{
		$request->getSession()->set('_locale', $locale);

		if($referer = $request->headers->get('referer')) {
			$refererPathInfo = Request::create($referer)->getPathInfo();

			if($refererRoute = $router->match($refererPathInfo)['_route']){
				$refererRouteParams = $router->match($refererPathInfo)['_route_params'] ?? [];
				$refererRouteParams['_locale'] = $locale;
				return $this->redirectToRoute($refererRoute, $refererRouteParams, Response::HTTP_SEE_OTHER);
			}
		}

		return $this->redirectToRoute('home');
	}
}