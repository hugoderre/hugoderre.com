<?php

namespace App\Doctrine\Listener;

use App\Entity\Tag;
use Symfony\Component\String\Slugger\SluggerInterface;

class TagListener
{

    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Tag $tag)
    {
        if(empty($tag->getSlug()) && $name = $tag->getName()) {
            $slug = strtolower($this->slugger->slug($name));
            $tag->setSlug($slug);
        }
    }
}
