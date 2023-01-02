<?php

namespace App\Controller\Admin\Crud;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

	public function configureActions(Actions $actions): Actions
    {
		return $actions
			->remove(Crud::PAGE_INDEX, Crud::PAGE_NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('post', 'Post')->hideOnForm(),
            TextareaField::new('content'),
            TextField::new('authorName'),
            EmailField::new('authorEmail'),
			DateTimeField::new('createdAt')->hideOnForm(),
            NumberField::new('spamScore'),
            ChoiceField::new('status')->setChoices(array_flip(Comment::getStatusList())),
        ];
    }
}
