<?php

namespace App\EventSubscriber;

use App\Entity\Post;
use App\Entity\User;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $slugger;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $hasher)
	{
		$this->hasher = $hasher;
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityUpdatedEvent::class => [
				['preUpdatePost'], 
				['preUpdateUser']
			]
        ];
    }

    public function preUpdatePost(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Post)) {
            return;
        }

        $slug = $this->slugger->slug($entity->getTitle());
        $entity->setSlug($slug);

        $entity->setUpdatedAt(new DateTimeImmutable());
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