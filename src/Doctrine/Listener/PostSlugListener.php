<?php

namespace App\Doctrine\Listener;

use App\Entity\Post;
use DateTimeImmutable;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostSlugListener
{

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Post $post, LifecycleEventArgs $event)
    {
        if(empty($post->getSlug()) && $title = $post->getTitle()) {
            $slug = strtolower($this->slugger->slug($title));
            $post->setSlug($slug);
        }

        if(empty($post->getCreatedAt())) {
            $post->setCreatedAt(new DateTimeImmutable());
        }
    }
}
