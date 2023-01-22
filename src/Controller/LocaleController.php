<?php

namespace App\Controller;

use App\Helpers\LocaleHelpers;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class LocaleController extends AbstractController
{
	#[Route('/change_locale/{locale}', name: 'change_locale', requirements: ['locale' => '%app_locales%'])]
	public function changeLocale($locale, Request $request, RouterInterface $router, LocaleHelpers $localeHelpers, LoggerInterface $logger): Response
	{
		$request->getSession()->set('_locale', $locale);

		if($referer = $request->headers->get('referer')) {
			$refererPathInfo = Request::create($referer)->getPathInfo();

			try {
				$refererRouteInfo = $router->match($refererPathInfo);
			} catch (ResourceNotFoundException $e) {
				return $this->redirectToRoute('home');
			}

			if($localeHelpers->isLocalizedRoute($refererRouteInfo['_route'])){
				$refererRouteParams = $refererRouteInfo['_route_params'] ?? [];
				$refererRouteParams['_locale'] = $locale;

				try {
					return $this->redirectToRoute($refererRouteInfo['_route'], $refererRouteParams, Response::HTTP_SEE_OTHER);
				} catch (\Exception $e) {
					$logger->error($e->getMessage());
					return $this->redirectToRoute('home');
				}
			}
		}

		return $this->redirectToRoute('home');
	}
}