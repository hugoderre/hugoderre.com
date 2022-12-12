<?php

namespace App\Helpers;

use Symfony\Component\DependencyInjection\ContainerInterface;

class UploadsHelpers
{
	public $env;
	public $uploadsURIPrefix;

	public function __construct(ContainerInterface $container)
	{
		$this->env = $container->getParameter('kernel.environment');
		$this->uploadsURIPrefix = $container->getParameter('uploads_uri_prefix_' . $this->env);
	}

	public function getUploadsURIPrefix($subDir = '')
	{
		return $this->uploadsURIPrefix . $subDir;
	}
}