<?php

namespace App\Helpers;

use Symfony\Component\DependencyInjection\ContainerInterface;

class LocaleHelpers
{
	public $locales;

	public function __construct(ContainerInterface $container)
	{
		$this->locales = $container->getParameter('app_locales');
	}

	public function getLocalesList()
	{
		$localesArray = explode('|', $this->locales);
		return array_combine($localesArray, $localesArray);
	}
}