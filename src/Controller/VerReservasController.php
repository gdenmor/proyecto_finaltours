<?php

namespace App\Controller;

use App\Form\ReservaType;
use App\Repository\ReservaRepository;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use App\Service\Correo;
use App\Service\ReservasService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VerReservasController extends AbstractController
{
    #[Route('/reservas/{id_user}', name: 'app_reservas')]
    public function index(Request $request,$id_user,ReservasService $servreserva)
    {
        $reservas=$servreserva->reservasUsuario($id_user);
        
        return $this->render('ver_reservas/index.html.twig', [
            'reservas' => $reservas,
        ]);
    }

    #[Route('/cancela_reserva/{id_reserva}/{id_user}', name: 'app_cancela_reservas')]
    public function cancela(Correo $mailer,UserRepository $userRepository,$id_user,EntityManagerInterface $entityManagerInterface,ReservaRepository $reservaRepository,Request $request,$id_reserva,ReservasService $servreserva)
    {
        $reserva=$reservaRepository->find($id_reserva);
        $reserva->setEstado("CANCELADO");
        $entityManagerInterface->persist($reserva);
        $entityManagerInterface->flush();
        $user=$userRepository->find($id_user);
        $mailer->EnviaCorreo('La reserva ha sido cancelada exitosamente. 
        Si se arrepiente deberá de reservar nuevamente',$user->getEmail(),'Cancelación de reserva');
        return $this->redirectToRoute('app_reservas',['id_user'=>$id_user]);
    }

    #[Route('/edita_reserva/{id_reserva}/{id_user}/{id_tour}',name: 'app_edita_reserva')]
    public function EditaReserva(TourRepository $tourRepository, $id_tour,ValidatorInterface $val, UserRepository $userRepository,Correo $correo,EntityManagerInterface $entity,Request $request,ReservaRepository $reservaRepository,$id_reserva,$id_user){
        $user=$userRepository->find($id_user);
        $reserva=$reservaRepository->find($id_reserva);
        $num_personas=$reserva->getNumPersonas();
        $form=$this->createForm(ReservaType::class,$reserva);
        $form->handleRequest($request);
        $errores=[];
        $error="";
        if ($form->isSubmitted()&&$form->isValid()){
            $otrareserva=$reserva->getNumPersonas();
            $totalpersonas=$reservaRepository->numpersonas($id_tour);
            if ($user->getRoles()[0]=="ROLE_USER"&&self::validarAforo(($totalpersonas-$num_personas)+$otrareserva,$reserva->getTour()->getRuta()->getAforo())==false){
                $error="No se puede editar ya que supera al aforo";
            }else{
                $data=$form->getData();
                $entity->persist($data);
                $entity->flush();
                $correo->EnviaCorreo('La reserva ha sido editada correctamente',$user->getEmail(),'Reserva editada');
                return $this->redirectToRoute('app_reservas',['id_user'=>$id_user]);
            }
        }else if ($form->isSubmitted()){
            $errores=$val->validate($reserva);
        }

        return $this->render('editareserva.html.twig',[
            'form'=>$form->createView(),
            'errores'=>$errores,
            'error'=>$error
        ]);
    }

    public static function validarAforo($numeroPersonas,$aforoPermitido)
    {
        // Implementa tu lógica de validación de aforo aquí
        // Puedes comparar $numeroPersonas con el aforo permitido y devolver true o false según sea necesario
        return $numeroPersonas <= $aforoPermitido;
    }
}
