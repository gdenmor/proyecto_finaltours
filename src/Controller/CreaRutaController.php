<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreaRutaController extends AbstractController
{
    #[Route('/creaRuta', name: 'app_crea_ruta')]
    public function index(ItemRepository $itemrepo,UserRepository $userRepository): Response
    {
        $items=$itemrepo->findAll();
        $guias=$userRepository->muestraGuias();
        return $this->render('crea_ruta/index.html.twig', [
            'guias'=>$guias,
            'items'=>$items
        ]);
    }
}
