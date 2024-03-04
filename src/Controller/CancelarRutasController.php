<?php

namespace App\Controller;

use App\Repository\ReservaRepository;
use App\Repository\RutaRepository;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use App\Service\TourService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CancelarRutasController extends AbstractController
{
    //#[IsGranted('ROLE_ADMIN')]
    #[Route('/listatours', name: 'app_lista_tours')]
    public function index(Request $request,TourRepository $tours,ReservaRepository $reservaRepository): Response
    {
        $tour=$tours->findBy(['estado'=>"ACTIVADO"]);
        $num_reservas=[];
        for ($i=0;$i<count($tour);$i++){
            $num=$reservaRepository->reservatour($tour[$i]->getId());
            $num_reservas[$i]=$num;
        }
        return $this->render('cancelar_rutas/index.html.twig', [
            'rutas'=>$tour,
            'num_reservas'=>$num_reservas
        ]);
    }
    //#[IsGranted('ROLE_ADMIN')]
    #[Route('/cancelatour/{id}', name: 'app_cancela_tour')]
    public function cancelaRuta(Request $request,TourService $tour,$id)
    {
        $tour->cancelaTour($id);
        return $this->redirectToRoute('app_lista_tours');
    }
}
