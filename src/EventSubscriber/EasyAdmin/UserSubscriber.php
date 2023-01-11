<?php

namespace App\EventSubscriber\EasyAdmin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserSubscriber implements EventSubscriberInterface
{
	private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
	{
		$this->hasher = $hasher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityUpdatedEvent::class => [
				['preUpdateUser']
			]
        ];
    }

    public function preUpdateUser(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

		if ($password = $entity->getPassword()) {
        	$entity->setPassword($this->hasher->hashPassword($entity, $password));
		}
    }
}