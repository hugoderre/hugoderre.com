<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;

class MediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Media::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ImageField::new('imageFileName')->setBasePath('/uploads/')->setLabel('Image')->onlyOnIndex(),
            TextField::new('imageFile')->setFormType(VichFileType::class)->onlyWhenCreating(),
            TextField::new('imageFileName')->hideOnForm()->setLabel('Nom du fichier'),
            NumberField::new('imageSize')->hideOnForm()->setLabel('Taille'),
            DateTimeField::new('ImageUploadedAt')->hideOnForm()->setLabel('Uploadée le'),
            // TextField::new('author')->hideOnForm()->setLabel('Auteur')
        ];
    }
}
