<?php

namespace App\Controller;

use App\Entity\Project;
use App\Helpers\UploadsHelpers;
use App\Repository\ProjectRepository;
use App\Trait\PostTypeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
	use PostTypeTrait;

    #[Route('/projets', name: 'projects')]
    public function projects(ProjectRepository $projectRepository, UploadsHelpers $uploadsHelper): Response
    {
        $projects = $projectRepository->findBy( ['status' => 'publish'] );

		usort($projects, function($a, $b) {
			return $a->getListOrder() <=> $b->getListOrder();
		});
        
        return $this->render('projects/projects.html.twig', [
            'projects' => $projects,
            'page'  => 'projects',
			'uploadsBasePath' => $uploadsHelper->getUploadsBasePath('/media/')
        ]);
        
    }

    #[Route('/projets/{slug}', name: 'project_view')]
    public function project(Project $project, UploadsHelpers $uploadsHelper): Response
    {
		if(!$this->canUserView($project)) {
			throw $this->createNotFoundException('Project not found');
		}
		
        return $this->render('projects/project.html.twig', [
            'project'      => $project,
            'page'      => 'project',
			'uploadsBasePath' => $uploadsHelper->getUploadsBasePath('/media/')
        ]);
    }
}