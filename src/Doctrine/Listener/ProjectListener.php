<?php

namespace App\Doctrine\Listener;

use App\Entity\Project;
use DateTimeImmutable;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProjectListener
{

    private $slugger;

    public function __construct(SluggerInterface $slugger, Security $security)
    {
        $this->slugger = $slugger;
        $this->security = $security;
    }

    public function prePersist(Project $project)
    {
        if(empty($project->getSlug()) && $title = $project->getName()) {
            $slug = strtolower($this->slugger->slug($title));
            $project->setSlug($slug);
        }

        if(empty($project->getCreatedAt())) {
            $project->setCreatedAt(new DateTimeImmutable());
        }
    }
}