<?php

namespace App\Trait;

trait PostTypeTrait
{
	public function canUserView($post): bool
	{
		if($post->getStatus() === 'publish') {
			return true;
		}
		if($this->isGranted('ROLE_ADMIN')) {
			return true;
		}
		if($post->getAuthor() === $this->getUser()) {
			return true;
		}
		return false;
	}
}