<?php

namespace App\Controller\Admin;

use App\Entity\Informe;
use App\Entity\Item;
use App\Entity\Localidad;
use App\Entity\Provincia;
use App\Entity\Reserva;
use App\Entity\Ruta;
use App\Entity\Tour;
use App\Entity\User;
use App\Entity\Valoracion;
use App\Repository\InformeRepository;
use App\Repository\ItemRepository;
use App\Repository\RutaRepository;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'dashboard')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/rutas', name: 'rutas')]
    public function rutas(UserRepository $userRepository): Response
    {
        $guias=$userRepository->muestraGuias();
        return $this->render('crea_ruta/index.html.twig',[
            'guias'=>$guias
        ]);
    }

    #[Route('/admin/delete/rutas', name: 'rutasdelete')]
    public function rutasdelete(InformeRepository $informeRepository,TourRepository $tourRepository,RutaRepository $rutaRepository,AdminContext $context,EntityManagerInterface $entityManagerInterface): Response
    {
        $id = $context->getEntity()->getInstance()->getId();
        $ruta = $rutaRepository->find($id);

        $entityManagerInterface->remove($ruta);
        $entityManagerInterface->flush();
        return $this->redirect("http://localhost:8000/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CRutaCrudController");
    }

    #[Route('/horario',name: 'horarioo')]
    public function horario(): Response {
        return $this->render('horario.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Proyecto Final');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Horarios');
        yield MenuItem::linkToRoute('Horario', 'fas fa-clock', "horarioo");
        yield MenuItem::section('Mantenimiento de entidades');
        yield MenuItem::linkToCrud('Usuarios', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Items', 'fas fa-shopping-bag', Item::class);
        yield MenuItem::linkToCrud('Reservas', 'fas fa-calendar', Reserva::class);
        yield MenuItem::linkToCrud('Valoraciones', 'fas fa-star', Valoracion::class);
        yield MenuItem::linkToCrud('Informes', 'fas fa-file', Informe::class);
        yield MenuItem::linkToCrud('Localidades', 'fas fa-map-marker-alt', Localidad::class);
        yield MenuItem::linkToCrud('Provincias', 'fas fa-map-marked-alt', Provincia::class);
        yield MenuItem::linkToCrud('Rutas', 'fas fa-map', Ruta::class);
        yield MenuItem::linkToCrud('Tours', 'fas fa-suitcase', Tour::class);
    }
}
