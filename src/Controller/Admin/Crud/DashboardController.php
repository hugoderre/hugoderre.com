<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Comment;
use App\Entity\Media;
use App\Entity\Post;
use App\Entity\Project;
use App\Entity\Tag;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hugo Derré');
    }

	public function configureAssets(): Assets
	{
		return parent::configureAssets()
            ->addWebpackEncoreEntry('admin');
	}
	
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Articles', 'fa fa-pencil', Post::class);
        yield MenuItem::linkToCrud('Commentaires', 'fa fa-comment', Comment::class);
        yield MenuItem::linkToCrud('Project', 'fa fa-diagram-project', Project::class);
        yield MenuItem::linkToCrud('Tag', 'fa fa-tags', Tag::class);
        yield MenuItem::linkToCrud('Medias', 'fa fa-image', Media::class);
        yield MenuItem::linkToCrud('Comptes', 'fa fa-users', User::class);
    }
}
