<?php

namespace App\Integration;

use MailchimpMarketing\ApiClient;

class MailchimpService
{
	private ApiClient $mailchimp;

	public function __construct(string $MAILCHIMP_KEY, string $MAILCHIMP_SERVER_PREFIX)
	{
		$this->mailchimp = new ApiClient();
		$this->mailchimp->setConfig([
			'apiKey' => $MAILCHIMP_KEY,
			'server' => $MAILCHIMP_SERVER_PREFIX,
		]);
	}

	public function addListMember(string $list_id, array $data)
	{
		return $this->mailchimp->lists->addListMember($list_id, $data);
	}
}