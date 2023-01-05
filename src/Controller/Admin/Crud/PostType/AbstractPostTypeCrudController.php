<?php

namespace App\Controller\Admin\Crud\PostType;

use App\Entity\AbstractPostType;
use Doctrine\ORM\EntityManagerInterface;
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
		$this->addDuplicateAction($actions);
		$this->addViewAction($actions);

		return $actions;
	}

	protected function addDuplicateAction(Actions &$actions)
	{
		$duplicatePostAction = Action::new('duplicate', 'Dupliquer', 'fa-solid fa-copy')
			->linkToCrudAction('duplicate')
			->addCssClass('btn btn-primary')
			->setHtmlAttributes([
				'title' => 'Dupliquer cet article'
			]);

		$actions->add(Crud::PAGE_INDEX, $duplicatePostAction);
	}

	protected function addViewAction(Actions &$actions)
	{
		$viewAction = Action::new('view', 'Voir', 'fa-solid fa-arrow-up-right-from-square')
		->linkToRoute(static::getViewRouteName(), function ($entity): array {
			return [
				'slug' => $entity->getSlug()
			];
		})
		->setHtmlAttributes([
			'target' => '_blank'
		]);

		$actions->add(Crud::PAGE_INDEX, $viewAction);
		$actions->add(Crud::PAGE_EDIT, $viewAction);
	}

	public function duplicate(AdminContext $context, EntityManagerInterface $em)
	{
		$entity = $context->getEntity()->getInstance();

		if(!$entity instanceof AbstractPostType) {
			throw new \Exception('Entity must implement AbstractPostType');
		}

		$entity = clone $entity;
		$entity->setTitle($entity->getTitle() . ' (copie)');
		$entity->setSlug($entity->getSlug() . '-copie');
		$entity->setStatus($entity::STATUS_DRAFT);
		$entity->setCreatedAt(new \DateTimeImmutable());
		$entity->setAuthor($this->security->getUser());
		
		$em->persist($entity);
		$em->flush();

		$this->addFlash('success', 'L\'élément a été dupliqué avec succès.');

		return $this->redirect($context->getReferrer());
	}
}
