<?php

namespace App\EventSubscriber\EasyAdmin;

use App\Entity\PostType\Post;
use DateTime;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PostSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityUpdatedEvent::class => [
				['preUpdatePost']
			]
        ];
    }

    public function preUpdatePost(BeforeEntityUpdatedEvent $event)
	{
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Post)) {
            return;
        }

		if($entity->getStatus() === 'publish' && $entity->getPublishedAt() === null) {
			$entity->setPublishedAt(new DateTimeImmutable());
		}

        $entity->setUpdatedAt(new DateTimeImmutable());
    }
}