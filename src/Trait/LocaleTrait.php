<?php

namespace App\Trait;

use Symfony\Component\HttpFoundation\Response;

trait LocaleTrait
{
	public function redirectEntityToCurrentLocale($entity, $routeName): Response
	{
		return $this->redirectToRoute($routeName, [
			'slug' => $entity->getSlug(),
			'_locale' => $entity->getLang()
		]);
	}
}