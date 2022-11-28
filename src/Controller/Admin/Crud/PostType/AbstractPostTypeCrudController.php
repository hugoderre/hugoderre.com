<?php

namespace App\Controller\Admin\Crud\PostType;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Security\Core\Security;

abstract class AbstractPostTypeCrudController extends AbstractCrudController implements InterfacePostTypeCrudController
{
    public function __construct(Security $security)
    {
		$this->security = $security;
    }

	public function createEntity(string $entityFqcn) {
        $entity = new $entityFqcn();
		$entity->setAuthor($this->security->getUser());
        $entity->setStatus($entityFqcn::STATUS_DRAFT);
        return $entity;
    }

	public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

	public function configureActions(Actions $actions): Actions
	{
		$buttonViewPost = Action::new('view', 'Voir', 'fa-solid fa-arrow-up-right-from-square')
			->linkToRoute(static::getViewRouteName(), function ($entity): array {
				return [
					'slug' => $entity->getSlug()
				];
			})
			->setHtmlAttributes([
				'target' => '_blank'
			]);

		$actions->add(Crud::PAGE_INDEX, $buttonViewPost);
		$actions->add(Crud::PAGE_EDIT, $buttonViewPost);

		return $actions;
	}
}
