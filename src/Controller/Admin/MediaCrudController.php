<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Helpers\UploadsHelpers;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;

class MediaCrudController extends AbstractCrudController
{
	protected $uploadHelper;

	public function __construct(UploadsHelpers $uploadsHelper)
	{
		$this->uploadsHelper = $uploadsHelper;
	}

    public static function getEntityFqcn(): string
    {
        return Media::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ImageField::new('fileName')->setBasePath($this->uploadsHelper->getUploadsBasePath('/media/'))->setLabel('Fichier')->onlyOnIndex(),
            TextField::new('file')->setFormType(VichFileType::class)->onlyWhenCreating(),
            TextField::new('fileName')->hideOnForm()->setLabel('Nom du fichier'),
            NumberField::new('size')->hideOnForm()->setLabel('Taille'),
            DateTimeField::new('uploadedAt')->hideOnForm()->setLabel('Créé le'),
            AssociationField::new('author')->hideOnForm()->setLabel('Auteur')
        ];
    }
}
