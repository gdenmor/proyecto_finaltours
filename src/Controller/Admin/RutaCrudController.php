<?php

namespace App\Controller\Admin;

use App\Entity\Ruta;
use App\Repository\ItemRepository;
use App\Repository\LocalidadRepository;
use App\Repository\RutaRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RutaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ruta::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */

    public function configureActions(Actions $actions): Actions{
        $actions->update(Crud::PAGE_INDEX,Action::NEW,function(Action $acti){
            return $acti->linkToRoute("rutas");
        });

        $actions->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            return $action->linkToCrudAction('rutasedit');
        });

        $actions->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
            return $action->linkToRoute('rutasdelete');
        });

        return $actions;
    }

    #[Route('edit/rutas', name: 'rutasedit')]
    public function rutasedit(RutaRepository $rutaRepository,AdminContext $context,ItemRepository $itemRepository,LocalidadRepository $localidadRepository): Response
    {
        $ruta=$context->getEntity()->getInstance();
        $id=$ruta->getId();
        $items=$itemRepository->ItemsNoAsignados($itemRepository,$localidadRepository);
        return $this->render('crea_ruta/editindex.html.twig',[
            'ruta'=>$ruta,
            'todosLosItems'=>$items,
            'id'=>$id
        ]);
    }

    

    /*public function customNewAction(AdminContext $context): Response
    {
        // Implementa tu lógica personalizada aquí

        // Por ejemplo, puedes redirigir a una ruta personalizada
        return $this->redirectToRoute('tu_ruta_personalizada');
    }*/
}
