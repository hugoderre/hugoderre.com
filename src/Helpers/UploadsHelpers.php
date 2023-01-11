<?php

namespace App\Helpers;

class UploadsHelpers
{
	public function __construct(private $uploadsUriPrefix) {}

	public function getUploadsURIPrefix($subDir = '')
	{
		return $this->uploadsUriPrefix . $subDir;
	}
}