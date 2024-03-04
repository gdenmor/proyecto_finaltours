<?php

namespace App\Controller;

use App\Entity\Informe;
use App\Form\InformeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AsignaGuiaController extends AbstractController
{
    #[Route('/asignaguia', name: 'app_asigna_guia')]
    public function index(): Response
    {
        
        return $this->render('asigna_guia/index.html.twig', [
            'controller_name' => 'AsignaGuiaController',
        ]);
    }

    #[Route('/horario/{guia_id}', name: 'horario')]
    public function horario(): Response
    {
        
        return $this->render('horarioGuia.html.twig');
    }
}
