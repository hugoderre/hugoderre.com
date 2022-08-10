<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{

    #[Route('/projets', name: 'projects')]
    public function projects(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findBy( ['status' => 'publish'] );
        
        return $this->render('projects/projects.html.twig', [
            'projects' => $projects,
            'page'  => 'projects',
        ]);
        
    }

    #[Route('/projets/{slug}', name: 'project_view')]
    public function project(Project $project): Response
    {
        return $this->render('projects/project.html.twig', [
            'project'      => $project,
            'page'      => 'project',
        ]);
    }
}