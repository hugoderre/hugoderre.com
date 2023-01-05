<?php

namespace App\Controller\Admin\Crud\PostType;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ProjectCrudController extends AbstractPostTypeCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

	public static function getViewRouteName(): string
	{
		return 'project_view';
	}

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            AssociationField::new('thumbnail')->autocomplete(true),
            TextEditorField::new('content', 'Description')->setFormType(CKEditorType::class),
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
