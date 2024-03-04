<?php

namespace App\Controller\Admin;

use App\Entity\Tour;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class TourCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tour::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('Ruta'),
            AssociationField::new('Guia'),
            DateField::new('fecha'),
            TimeField::new('hora'),
        ];
    }

}
