<?php

namespace App\EventSubscriber;

use App\Repository\UserRestrictionRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RestrictIpAddressSubscriber implements EventSubscriberInterface
{
	private array $ipBanList;

    public function __construct(private UserRestrictionRepository $userRestrictionRepository)
	{
		$this->ipBanList = $this->userRestrictionRepository->getIpRestrictionList('ban');
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

        if ($this->userRestrictionRepository->isIpRestricted($event->getRequest()->getClientIp(), 'ban')) {
            throw new AccessDeniedHttpException('Access Denied');
        }
    }
}