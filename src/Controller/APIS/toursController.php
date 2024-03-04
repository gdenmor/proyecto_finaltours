<?php

namespace App\Controller\APIS;

use App\Repository\RutaRepository;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class toursController extends AbstractController
{
    private Security $security;
    public function __construct(Security $security){
        $this->security=$security;
    }
    #[Route('/apis/tours', name: 'app_apis_tours',methods:['GET'])]
    public function get(TourRepository $tourRepository): JsonResponse
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $tours=$tourRepository->findAll();
        $data=[];
        if ($tours){
            foreach ($tours as $tour){
                $data[]=[
                    'id'=>$tour->getId(),
                    'ruta'=>[
                        'id'=>$tour->getRuta()->getId(),
                        'titulo'=>$tour->getRuta()->getTitulo(),
                        'fecha_inicio'=>$tour->getRuta()->getFechaInicio(),
                        'fecha_fin'=>$tour->getRuta()->getFechaFin(),
                        'aforo'=>$tour->getRuta()->getAforo()

                    ],
                    'guia'=>[
                        'id_guia'=>$tour->getGuia()->getId(),
                        'nombre'=>$tour->getGuia()->getUsername(), 
                    ],
                    'fecha'=>$tour->getFecha(),
                    'hora'=>$tour->getHora(),
                    //'reservas'=>$tour->getReserva()
                ];
            }
            return $this->json($data,200);
        }else{
            return $this->json(["error"=>"Error"]);
        }
    }

    #[Route('/apis/tour/{id}', name: 'app_apis_tourss',methods:['GET'])]
    public function getiDs(TourRepository $tourRepository,$id): JsonResponse
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $tour=$tourRepository->find($id);
        $data="";
        if ($tour){
                $data=[
                    'id'=>$tour->getId(),
                    'ruta'=>[
                        'id'=>$tour->getRuta()->getId(),
                        'titulo'=>$tour->getRuta()->getTitulo(),
                        'fecha_inicio'=>$tour->getRuta()->getFechaInicio(),
                        'fecha_fin'=>$tour->getRuta()->getFechaFin(),
                        'geolocalizacion'=>$tour->getRuta()->getPuntoEncuentro(),
                        'aforo'=>$tour->getRuta()->getAforo()
                    ],
                    'guia'=>[
                        'id_guia'=>$tour->getGuia()->getId(),
                        'nombre'=>$tour->getGuia()->getUsername(), 
                    ],
                    'fecha'=>$tour->getFecha(),
                    'hora'=>$tour->getHora(),
                    //'reservas'=>$tour->getReserva()
                ];
            return $this->json($data,200);
        }else{
            return $this->json(["error"=>"Error"]);
        }
    }

    #[Route('/apis/toursguia/{id}', name: 'guia', methods: ['GET'])]
public function getTours(TourRepository $tourRepository, $id, UserRepository $userRepository): JsonResponse
{
    if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
        return $this->json(['message' => 'Acceso no autorizado'], 401);
    }
    $guia = $userRepository->find($id);
    $tours = $tourRepository->findBy(["Guia" => $guia]);

    $data = [];

    if ($tours) {
        foreach ($tours as $tour) {
            $data[] = [
                'id' => $tour->getId(),
                'ruta' => [
                    'id' => $tour->getRuta()->getId(),
                    'titulo' => $tour->getRuta()->getTitulo(),
                    'fecha_inicio' => $tour->getRuta()->getFechaInicio(),
                    'fecha_fin' => $tour->getRuta()->getFechaFin(),
                    'geolocalizacion' => $tour->getRuta()->getPuntoEncuentro(),
                    'aforo'=>$tour->getRuta()->getAforo()
                ],
                'guia' => [
                    'id_guia' => $tour->getGuia()->getId(),
                    'nombre' => $tour->getGuia()->getUsername(),
                ],
                'fecha' => $tour->getFecha(),
                'hora' => $tour->getHora(),
            ];
        }

        return $this->json($data, 200);
    } else {
        return $this->json(["error" => "Error"]);
    }
}
    #[Route('/apis/tour/{id}', name: 'app_apis_tourss', methods:['GET'])]
    public function getiD(TourRepository $tourRepository,$id): JsonResponse
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $tour=$tourRepository->find($id);
        $data="";
        if ($tour){
                $data=[
                    'id'=>$tour->getId(),
                    'ruta'=>[
                        'id'=>$tour->getRuta()->getId(),
                        'titulo'=>$tour->getRuta()->getTitulo(),
                        'fecha_inicio'=>$tour->getRuta()->getFechaInicio(),
                        'fecha_fin'=>$tour->getRuta()->getFechaFin(),
                        'geolocalizacion'=>$tour->getRuta()->getPuntoEncuentro(),
                        'aforo'=>$tour->getRuta()->getAforo()
                    ],
                    'guia'=>[
                        'id_guia'=>$tour->getGuia()->getId(),
                        'nombre'=>$tour->getGuia()->getUsername(), 
                    ],
                    'fecha'=>$tour->getFecha(),
                    'hora'=>$tour->getHora(),
                    //'reservas'=>$tour->getReserva()
                ];
            return $this->json($data,200);
        }else{
            return $this->json(["error"=>"Error"]);
        }
    }

    #[Route('/apis/ruta/{id}', name: 'ruta', methods: ['GET'])]
    public function getRuta(RutaRepository $rutaRepository, $id, UserRepository $userRepository): JsonResponse
    {

        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $ruta=$rutaRepository->find($id);

        $data = [];

        if ($ruta) {
                $data= [
                    'ruta' => [
                        'id' => $ruta->getId(),
                        'titulo' => $ruta->getTitulo(),
                        'descripcion' => $ruta->getDescripcion(),
                        'foto'=>$ruta->getFoto(),
                        'fecha_inicio' => $ruta->getFechaInicio(),
                        'fecha_fin' => $ruta->getFechaFin(),
                        'coordenadas' => $ruta->getPuntoEncuentro(),
                        'aforo'=>$ruta->getAforo()
                    ]
                ];
            return $this->json($data, 200);
        } else {
            return $this->json(["error" => "Error"]);
        }
    }
}


