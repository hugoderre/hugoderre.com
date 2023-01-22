<?php

namespace App\Helpers;

use Symfony\Component\Routing\RouterInterface;

class LocaleHelpers
{
	public function __construct(private string $locales, private RouterInterface $router) {}

	public function getLocalesList()
	{
		$localesArray = explode('|', $this->locales);
		return array_combine($localesArray, $localesArray);
	}

	public function isLocalizedRoute($routeName)
	{
		if($route = $this->router->getRouteCollection()->get($routeName)) {
			return array_key_exists('_locale', $route->getRequirements());
		}
		return false;
	}
}