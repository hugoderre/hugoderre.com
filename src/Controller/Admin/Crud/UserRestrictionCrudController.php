<?php

namespace App\Controller\Admin\Crud;

use App\Entity\UserRestriction;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserRestrictionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserRestriction::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('ip'),
            BooleanField::new('ban'),
        ];
    }
}
