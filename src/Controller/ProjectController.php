<?php

namespace App\Controller;

use App\Entity\PostType\Project;
use App\Repository\ProjectRepository;
use App\Trait\LocaleTrait;
use App\Trait\PostTypeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
	use PostTypeTrait;
	use LocaleTrait;

    #[Route('/projects', name: 'projects')]
    public function projects(ProjectRepository $projectRepository, Request $request): Response
    {
        $projects = $projectRepository->findBy([
			'status' => 'publish',
			'lang' => $request->getLocale()
		]);

		usort($projects, function($a, $b) {
			return $a->getListOrder() <=> $b->getListOrder();
		});
        
        return $this->render('projects/projects.html.twig', [
            'projects' => $projects,
            'page'  => 'projects'
        ]);
        
    }

    #[Route('/projects/{slug}', name: 'project_view')]
    public function project(Project $project, Request $request): Response
    {
		if(!$this->canUserView($project)) {
			throw $this->createNotFoundException('Project not found');
		}

		if($project->getLang() !== $request->getLocale()) {
			return $this->redirectEntityToCurrentLocale($project, 'project_view');
		}
		
        return $this->render('projects/project.html.twig', [
            'project'      => $project,
			'translatedEntities' => ['entities' => $project->getTranslatedProjects()->toArray(), 'fallback_route' => 'projects'],
            'page'      => 'project',
        ]);
    }

	#[Route(name: 'project_locale_redirect')]
	public function projectLocaleRedirect(Project $project): Response
	{
		return $this->redirectEntityToCurrentLocale($project, 'project_view');
	}
}