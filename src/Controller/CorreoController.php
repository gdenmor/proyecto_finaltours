<?php

namespace App\Controller;

use App\Service\Correo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CorreoController extends AbstractController
{
    #[Route('/correo', name: 'app_correo')]
    public function index(Correo $correo): Response
    {
        //$correo->EnviaCorreo();
        return $this->render('correo/index.html.twig', [
            'controller_name' => 'CorreoController',
        ]);
    }
}
