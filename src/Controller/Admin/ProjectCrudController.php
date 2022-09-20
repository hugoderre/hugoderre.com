<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ProjectCrudController extends AbstractCrudController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Project::class;
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
			NumberField::new('list_order')->onlyOnForms(),
            ChoiceField::new('status')->setChoices(array_flip(Project::getStatusList())),
            DateTimeField::new('createdAt')->hideOnForm(),
        ];
    }
}
