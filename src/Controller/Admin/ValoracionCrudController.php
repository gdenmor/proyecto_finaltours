<?php

namespace App\Controller\Admin;

use App\Entity\Valoracion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ValoracionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Valoracion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('Reserva'),
            AssociationField::new('Tour'),
            NumberField::new('estrellas'),
            TextField::new('comentario')
        ];
    }
}
