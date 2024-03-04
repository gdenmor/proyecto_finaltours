<?php

namespace App\Controller\Admin;

use App\Entity\Informe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InformeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Informe::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('Tour'),
            TextEditorField::new('observaciones'),
            TextField::new('dinero'),
            ImageField::new('imagen')->setUploadDir('public/css/imagenes')->setBasePath('css/imagenes')
        ];
    }
}
