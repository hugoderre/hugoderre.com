<?php

namespace App\Controller\Admin\Crud\PostType;

use App\Entity\PostType\Post;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class PostCrudController extends AbstractPostTypeCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

	public static function getViewRouteName(): string
	{
		return 'post_view';
	}

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
			TextField::new('slug')->hideOnForm(),
            TextEditorField::new('content', 'Contenu')->setFormType(CKEditorType::class)->hideOnIndex(),
            AssociationField::new('tags')->autocomplete(true),
            AssociationField::new('relatedPosts')->autocomplete(true),
            AssociationField::new('translatedPosts')->autocomplete(true),
            AssociationField::new('author')->autocomplete(true),
			TextareaField::new('metaDescription')->onlyOnForms(),
			AssociationField::new('metaImage')->autocomplete(true)->onlyOnForms(),
            ChoiceField::new('status')->setChoices(array_flip(Post::getStatusList())),
			ChoiceField::new('lang')->setChoices($this->localesHelpers->getLocalesList()),
            DateTimeField::new('publishedAt')->hideOnForm(),
        ];
    }
}
