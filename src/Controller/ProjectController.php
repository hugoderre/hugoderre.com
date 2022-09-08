<?php

namespace App\Controller;

use App\Entity\Project;
use App\Helpers\UploadsHelperService;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{

    #[Route('/projets', name: 'projects')]
    public function projects(ProjectRepository $projectRepository, UploadsHelperService $uploadsHelper): Response
    {
        $projects = $projectRepository->findBy( ['status' => 'publish'] );
        
        return $this->render('projects/projects.html.twig', [
            'projects' => $projects,
            'page'  => 'projects',
			'uploadsBasePath' => $uploadsHelper->getUploadsBasePath('/media/')
        ]);
        
    }

    #[Route('/projets/{slug}', name: 'project_view')]
    public function project(Project $project, UploadsHelperService $uploadsHelper): Response
    {
        return $this->render('projects/project.html.twig', [
            'project'      => $project,
            'page'      => 'project',
			'uploadsBasePath' => $uploadsHelper->getUploadsBasePath('/media/')
        ]);
    }
}