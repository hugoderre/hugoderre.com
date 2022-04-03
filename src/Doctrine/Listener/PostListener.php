<?php

namespace App\Doctrine\Listener;

use App\Entity\Post;
use DateTimeImmutable;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostListener
{

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Post $post)
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
