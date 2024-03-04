<?php

namespace App\Controller\APIS;

use App\Entity\Valoracion;
use App\Repository\ReservaRepository;
use App\Repository\TourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiValoracionController extends AbstractController
{
    private Security $security;
    public function __construct(Security $security){
        $this->security=$security;
    }
    #[Route('/api/addvaloracion', name: 'app_api_valoracion',methods:["POST"])]
    public function post(EntityManagerInterface $entityManagerInterface,Request $request,ReservaRepository $reservaRepository,TourRepository $tourRepository): JsonResponse
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $json=json_decode($request->getContent(),true);
        $reserva=$reservaRepository->find($json['idReserva']);
        $tour=$tourRepository->find($json['idtour']);
        $valoracion=new Valoracion();
        $valoracion->setReserva($reserva);
        $valoracion->setTour($tour);
        $valoracion->setComentario($json['comentarios']);
        $valoracion->setEstrellas($json['valoracion']);
        $entityManagerInterface->persist($valoracion);
        $entityManagerInterface->flush();
        return $this->json(["message"=>"Valorado correctamente"]);
    }
}
