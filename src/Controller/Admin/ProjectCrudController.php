<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Security\Core\Security;

class ProjectCrudController extends AbstractCrudController
{
    public function __construct(Security $security)
    {
		$this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

	public function createEntity(string $entityFqcn) {
        $entity = new $entityFqcn();
		$entity->setAuthor($this->security->getUser());
        $entity->setStatus($entityFqcn::STATUS_DRAFT);
		$entity->setListOrder(0);
        return $entity;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            AssociationField::new('thumbnail')->autocomplete(true),
            TextEditorField::new('description', 'Description')->setFormType(CKEditorType::class),
            TextField::new('githubUrl')->onlyOnForms(),
            TextField::new('websiteUrl')->onlyOnForms(),
            AssociationField::new('gallery')->autocomplete(true),
            AssociationField::new('tags')->autocomplete(true),
			AssociationField::new('author')->autocomplete(true),
			NumberField::new('listOrder')->onlyOnForms(),
			TextareaField::new('metaDescription')->onlyOnForms(),
			AssociationField::new('metaImage')->autocomplete(true)->onlyOnForms(),
            ChoiceField::new('status')->setChoices(array_flip(Project::getStatusList())),
            DateTimeField::new('createdAt')->hideOnForm(),
        ];
    }
}
