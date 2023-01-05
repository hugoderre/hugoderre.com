<?php

namespace App\Doctrine\Listener;

use App\Entity\PostType\Project;
use DateTimeImmutable;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProjectListener
{
    private $slugger;
	private $security;

    public function __construct(SluggerInterface $slugger, Security $security)
    {
        $this->slugger = $slugger;
        $this->security = $security;
    }

    public function prePersist(Project $project)
    {
        if(empty($project->getSlug()) && $title = $project->getTitle()) {
            $slug = strtolower($this->slugger->slug($title));
            $project->setSlug($slug);
        }

        if(empty($project->getCreatedAt())) {
            $project->setCreatedAt(new DateTimeImmutable());
        }

		if(empty($project->getAuthor())) {
            $project->setAuthor($this->security->getUser());
        }
    }
}
