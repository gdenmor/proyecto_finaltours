<?php

namespace App\Controller\APIS;

use App\Entity\Localidad;
use App\Repository\ItemRepository;
use App\Repository\LocalidadRepository;
use App\Repository\ProvinciaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ApiItemController extends AbstractController
{
    #[Route('/veritems', name: 'app_itemss',methods:["GET"])]
    public function index(ItemRepository $itemRepository,Security $security): JsonResponse
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }

        $items=$itemRepository->findAll();
        $data=[];
        if ($items){
            for ($i=0;$i<count($items);$i++){
                $data[]=[
                    'id'=>$items[$i]->getId(),
                    'titulo'=>$items[$i]->getTitulo(),
                    'descripcion'=>$items[$i]->getDescripcion(),
                    'foto'=>$items[$i]->getFoto(),
                    'localidad'=>[
                        'id_localidad'=>$items[$i]->getLocalidad()->getId(),
                        'nombre_localidad'=>$items[$i]->getLocalidad()->getNombre(),
                        'provincia'=>[
                            'id_provincia'=>$items[$i]->getLocalidad()->getProvincia()->getId(),
                            'nombre_provincia'=>$items[$i]->getLocalidad()->getProvincia()->getNombre(),
                        ]
                    ]
                ];
            }
            return $this->json($data,200);
        }else{
            return $this->json(['message' => 'No existen items'], 400);
        }
        
    }

    #[Route('/veritems/{provincia}', name: 'app_items',methods:["GET"])]
    public function indexprovincia(Security $security,ItemRepository $itemRepository,$provincia,LocalidadRepository $localidadRepository): JsonResponse
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $items=$itemRepository->ItemsdeTourPorProvincia($itemRepository,$localidadRepository,$provincia);
        $data=[];
        if ($items){
            for ($i=0;$i<count($items);$i++){
                $data[]=[
                    'id'=>$items[$i]->getId(),
                    'titulo'=>$items[$i]->getTitulo(),
                    'descripcion'=>$items[$i]->getDescripcion(),
                    'foto'=>$items[$i]->getFoto(),
                    'localidad'=>[
                        'id_localidad'=>$items[$i]->getLocalidad()->getId(),
                        'nombre_localidad'=>$items[$i]->getLocalidad()->getNombre(),
                        'provincia'=>[
                            'id_provincia'=>$items[$i]->getLocalidad()->getProvincia()->getId(),
                            'nombre_provincia'=>$items[$i]->getLocalidad()->getProvincia()->getNombre(),
                        ]
                    ]
                ];
            }
            return $this->json($data,200);
        }else{
            return $this->json(['message' => 'No existen items'], 400);
        }
        
    }
}