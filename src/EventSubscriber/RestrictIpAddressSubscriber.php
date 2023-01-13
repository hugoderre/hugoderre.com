<?php

namespace App\EventSubscriber;

use App\Repository\UserRestrictionRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RestrictIpAddressSubscriber implements EventSubscriberInterface
{
	private array $ipBlacklist;

    public function __construct(UserRestrictionRepository $userRestrictionRepository)
	{
		$this->ipBlacklist = $userRestrictionRepository->getIpBlacklist();
	}

    public static function getSubscribedEvents(): array
    {
        return [RequestEvent::class => 'onKernelRequest'];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if (in_array($event->getRequest()->getClientIp(), $this->ipBlacklist, true)) {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }
}