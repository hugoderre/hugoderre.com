<?php

namespace App\EventSubscriber\EasyAdmin;

use App\Entity\PostType\Project;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProjectSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityUpdatedEvent::class => [
				['preUpdateProject']
			],
        ];
    }

    public function preUpdateProject(BeforeEntityUpdatedEvent $event)
	{
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Project)) {
            return;
        }

		if($entity->getStatus() === 'publish' && $entity->getPublishedAt() === null) {
			$entity->setPublishedAt(new DateTimeImmutable());
		}

        $entity->setUpdatedAt(new DateTimeImmutable());
    }
}