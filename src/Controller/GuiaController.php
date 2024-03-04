<?php

namespace App\Controller;

use App\Entity\Informe;
use App\Form\InformeType;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use App\Service\GuiaService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GuiaController extends AbstractController
{
    #[Route('/listado/{id}', name: 'app_guia')]
    public function index(Request $request,$id,GuiaService $guia): Response
    {
        $toursguia=$guia->toursGuia($id);
        return $this->render('guia/index.html.twig', [
            'tours'=>$toursguia
        ]);
    }

    #[Route('informe/{id}',name:'informe_ruta')]
    public function informe(ValidatorInterface $val,Request $request,$id,EntityManagerInterface $entityManagerInterface){
        $informe=new Informe();
        $form=$this->createForm(InformeType::class,$informe);
        $form->handleRequest($request);
        $errores=[];
        if ($form->isSubmitted()&&$form->isValid()){
            $archivo = $form->get('imagen')->getData();

            // Directorio donde se almacenarán los archivos
            $directorioDestino = $this->getParameter('kernel.project_dir') . '/public/css/imagenes';

            // Generar un nombre único para el archivo
            $nombreArchivo = $archivo->getClientOriginalName();

            // Mover el archivo al directorio de destino
            $archivo->move($directorioDestino, $nombreArchivo);

            $informefinal=$form->getData();
            $informefinal->setImagen($nombreArchivo);
            $entityManagerInterface->persist($informefinal);
            $entityManagerInterface->flush();
            $this->addFlash("Exito","Informe creado correctamente");
        }else if ($form->isSubmitted()){
            $errores=$val->validate($informe);
        }

        return $this->render('informe.html.twig',[
            'vista'=>$form->createView(),
            'errores'=>$errores
        ]);
    }

    #[Route('/pasarlista/{id}/{id_tour}', name: 'app_pasarlista')]
    public function pasarlista(Request $request,$id,$id_tour,TourRepository $tourRepository): Response{
        $tour=$tourRepository->find($id_tour);
        $campo=[];
        $campo=['Usuario','Número personas','Personas que asisten','Acciones'];
        return $this->render('pasarlista.html.twig',[
            'tour'=>$tour,
            'columnas'=>$campo
        ]);
    }

    #[Route('/lista/{id}', name: 'app_lista')]
    public function lista(Request $request,$id,UserRepository $userrepo,TourRepository $tourRepository): Response{
        $user=$userrepo->find($id);
        $toursguia=$tourRepository->findBy(["Guia"=>$user]);
        return $this->render('lista.html.twig', [
            'tours'=>$toursguia
        ]);
    }

}
