<?php

namespace App\Helpers;

use Symfony\Component\DependencyInjection\ContainerInterface;

class UploadsHelpers
{
	public $env;
	public $uploadsBasePath;

	public function __construct(ContainerInterface $container)
	{
		$this->env = $container->getParameter('kernel.environment');
		$this->uploadsBasePath = $container->getParameter('uploads_base_' . $this->env);
	}

	public function getUploadsBasePath($subDir = '')
	{
		return $this->uploadsBasePath . $subDir;
	}
}