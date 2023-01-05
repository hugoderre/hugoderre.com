<?php

namespace App\Controller\Admin\Crud\PostType;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Security\Core\Security;

abstract class AbstractPostTypeCrudController extends AbstractCrudController implements InterfacePostTypeCrudController
{
	protected $security;

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
		$viewPostAction = Action::new('view', 'Voir', 'fa-solid fa-arrow-up-right-from-square')
			->linkToRoute(static::getViewRouteName(), function ($entity): array {
				return [
					'slug' => $entity->getSlug()
				];
			})
			->setHtmlAttributes([
				'target' => '_blank'
			]);

		$actions->add(Crud::PAGE_INDEX, $viewPostAction);
		$actions->add(Crud::PAGE_EDIT, $viewPostAction);

		// Add duplicate action button
		$duplicatePostAction = Action::new('duplicate', 'Dupliquer', 'fa-solid fa-copy')
			->linkToCrudAction('duplicate')
			->addCssClass('btn btn-primary')
			->setHtmlAttributes([
				'title' => 'Dupliquer cet article'
			]);

		$actions->add(Crud::PAGE_INDEX, $duplicatePostAction);

		return $actions;
	}

	public function duplicate(AdminContext $context)
	{
		$entity = $context->getEntity()->getInstance();
		$entity->setTitle($entity->getTitle() . ' (copie)');
		$entity->setSlug($entity->getSlug() . '-copie');
		$entity->setStatus($entity->getStatus()::STATUS_DRAFT);
		$entity->setPublishedAt(null);
		$entity->setCreatedAt(new \DateTime());
		$entity->setAuthor($this->security->getUser());

		// $this->em->persist($entity);
		// $this->em->flush();

		// $this->addFlash('success', 'L\'article a été dupliqué avec succès.');

		// return $this->redirectToReferrer();
	}
}
