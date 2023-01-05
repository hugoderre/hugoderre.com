<?php

namespace App\Controller;

use App\Entity\PostType\Project;
use App\Repository\ProjectRepository;
use App\Trait\PostTypeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
	use PostTypeTrait;

    #[Route('/projects', name: 'projects', options: ['sitemap' => true])]
    public function projects(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findBy( ['status' => 'publish'] );

		usort($projects, function($a, $b) {
			return $a->getListOrder() <=> $b->getListOrder();
		});
        
        return $this->render('projects/projects.html.twig', [
            'projects' => $projects,
            'page'  => 'projects'
        ]);
        
    }

    #[Route('/projects/{slug}', name: 'project_view')]
    public function project(Project $project): Response
    {
		if(!$this->canUserView($project)) {
			throw $this->createNotFoundException('Project not found');
		}
		
        return $this->render('projects/project.html.twig', [
            'project'      => $project,
            'page'      => 'project'
        ]);
    }
}