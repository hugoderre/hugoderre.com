<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Security\Core\Security;

class PostCrudController extends AbstractCrudController
{
    public function __construct(Security $security)
    {
		$this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Post::class;
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

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
			TextField::new('slug')->hideOnForm(),
            TextEditorField::new('content', 'Contenu')->setFormType(CKEditorType::class),
            AssociationField::new('tags')->autocomplete(true),
            AssociationField::new('author')->autocomplete(true),
			TextareaField::new('metaDescription')->onlyOnForms(),
			AssociationField::new('metaImage')->autocomplete(true)->onlyOnForms(),
            ChoiceField::new('status')->setChoices(array_flip(Post::getStatusList())),
            DateTimeField::new('publishedAt')->hideOnForm(),
        ];
    }
}
