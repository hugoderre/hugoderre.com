<?php

namespace App\Helpers;

class LocaleHelpers
{
	public $locales;

	public function __construct(string $locales)
	{
		$this->locales = $locales;
	}

	public function getLocalesList()
	{
		$localesArray = explode('|', $this->locales);
		return array_combine($localesArray, $localesArray);
	}
}