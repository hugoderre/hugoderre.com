<?php

namespace App\Doctrine\Listener;

use App\Entity\Post;
use DateTimeImmutable;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostListener
{

    private $slugger;

    public function __construct(SluggerInterface $slugger, Security $security)
    {
        $this->slugger = $slugger;
        $this->security = $security;
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

		if($post->getStatus() === 'publish') {
			$post->setPublishedAt(new DateTimeImmutable());
		}

        if(empty($post->getAuthor())) {
            $post->setAuthor($this->security->getUser());
        }
    }
}
