<?php

namespace App\Controller;

use App\Entity\Reserva;
use App\Entity\Ruta;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\ReservaType;
use App\Repository\ItemRepository;
use App\Repository\LocalidadRepository;
use App\Repository\ReservaRepository;
use App\Repository\RutaRepository;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use App\Service\Autenticador;
use App\Service\Correo;
use App\Service\DispatcherService;
use App\Service\ReservasService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PrincipalController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(RutaRepository $rutaRepository,UserRepository $userRepository,PaginatorInterface $paginador,TourRepository $tourRepository,AuthenticationUtils $aut,Request $request,UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entity,SluggerInterface $slugger): Response
    {
        $error = $aut->getLastAuthenticationError();
        $lastUsername = $aut->getLastUsername();
        $tours=$tourRepository->ToursMejorValorados($rutaRepository,$userRepository);
        $paginado=$paginador->paginate($tours,$request->query->getInt('page', 1),3);
        return $this->render('landingpage.html.twig', [
            'last_username' => $lastUsername,
            'error'=>$error,
            'tours'=>$tours,
            'pagination'=>$paginado
        ]);
    }

    #[Route('/rol', name: 'app_rol')]
    public function rol(DispatcherService $servicio,AuthenticationUtils $aut,Request $request,UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entity,SluggerInterface $slugger)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_main');
        } else if ($this->isGranted("ROLE_USER")){
            $servicio->lanzaEvento();
            return $this->redirectToRoute('app_main');
        }else if ($this->isGranted("ROLE_GUIA")) {
            return $this->redirectToRoute('app_main');
        }
    }

    #[Route('/tours/{localidad}/{fecha_inicio}/{fecha_fin}', name: 'app_filtro')]
    public function filtro($fecha_inicio,$fecha_fin,AuthenticationUtils $aut,Request $request,PaginatorInterface $paginador,ReservaRepository $reservaRepository,TourRepository $tours,$localidad, RutaRepository $rutaRepository,UserRepository $userRepository): Response
    {
        $tourss=$tours->buscaToursByLocalidad($localidad,$rutaRepository,$userRepository,$fecha_inicio,$fecha_fin);
        $valoraciones=[];
        $error = $aut->getLastAuthenticationError();
        $lastUsername = $aut->getLastUsername();
        for ($i=0;$i<count($tourss);$i++){
            $valoraciones[]=number_format($reservaRepository->valoraciontour($tourss[$i]->getId()),2);
        }
        $pagina=$paginador->paginate($tourss,$request->get('page',1),6);
        return $this->render('filtro.html.twig', [
            'tours'=>$pagina,
            'valoraciones'=>$valoraciones,
            'error'=>$error,
            'last_username'=>$lastUsername
        ]);
    }

    #[Route('/registerr', name: 'app_registroo')]
    public function registro(UserPasswordHasherInterface $userPasswordHasher, ValidatorInterface $validador,AuthenticationUtils $aut,Request $request,PaginatorInterface $paginador,ReservaRepository $reservaRepository,TourRepository $tours, RutaRepository $rutaRepository,UserRepository $userRepository,EntityManagerInterface $entityManagerInterface): Response
    {
        $user=new User();
        $form=$this->createForm(RegistrationFormType::class,$user);
        $form->handleRequest($request);
        $errores=[];
        if ($form->isSubmitted()&&$form->isValid()){
            $user->setRoles(["ROLE_USER"]);
            $foto=$form->get('foto')->getData();
            $safeFilename="";
            if ($foto) {
                $originalFilename = pathinfo($foto->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $originalFilename;
            }
            $password=$form->get('password')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $newFilename = $safeFilename.'-'.uniqid().'.'.$foto->guessExtension();
            try {
                $foto->move(
                    $this->getParameter('kernel.project_dir') . '/public/css/imagenes',
                    $newFilename
                );
                $user->setFoto($newFilename);
                $entityManagerInterface->persist($user);
                $entityManagerInterface->flush();
            } catch (FileException $e) {
                
            }
            $this->addFlash('Exito',"Usuario creado correctamente");
        }else{
            if ($form->isSubmitted()){
                $errores=$validador->validate($user);
            }
        }
        return $this->render('registro.html.twig', [
            'errores'=>$errores,
            'vista'=>$form->createView()
        ]);
    }

    #[Route('/info/{id_tour}/{id_user}', name: 'app_info_tour')]
    public function info(ReservaRepository $reservaRepository,ValidatorInterface $val,Security $sec, Correo $correo,UserRepository $userRepository,EntityManagerInterface $entity,Request $request,AuthenticationUtils $aut,$id_tour,TourRepository $tourRepository,ItemRepository $itemRepository,LocalidadRepository $localidadRepository,$id_user=0): Response
    {
        $user = $userRepository->find($id_user);
        $reserva=new Reserva();
        $reserva->setFechaReserva(new \DateTime());

        $reserva->setUsuario($sec->getUser());
        $reserva->setEstado('ACEPTADO');
        $tour=$tourRepository->find($id_tour);
        $reserva->setTour($tour);
        $itemstour=$itemRepository->ItemsdeTour($tour->getId(),$itemRepository,$localidadRepository);
        $error = $aut->getLastAuthenticationError();
        $lastUsername = $aut->getLastUsername();

        $errores = [];

        $form=$this->createForm(ReservaType::class,$reserva);
        $form->handleRequest($request);

        $num_reservas=$reservaRepository->numpersonas($id_tour);
        
        if ($form->isSubmitted()&&$form->isValid()){
            $reservaf=$form->getData();
            if ($user==null){
                $this->addFlash('Error',"Debes de loguearte para poder reservar");
            }else if ($user->getRoles()[0]=="ROLE_USER"&&self::validarAforo($reserva->getNumPersonas()+$num_reservas,$reserva->getTour()->getRuta()->getAforo())==true){
                $correo->EnviaCorreo('<p>Has reservado correctamente. A continuación, te permitimos añadir tu reserva en un evento de google calendar</p><a href="https://www.google.com/calendar/render?action=TEMPLATE";">Crea evento en Google Calendar</a>',$user->getEmail(),'Reserva realizada');
                $this->addFlash('exito',"Reserva registrada perfectamente");
                $entity->persist($reserva);
                $entity->flush();
            }else if ($user->getRoles()[0]=="ROLE_USER"&&self::validarAforo($reserva->getNumPersonas()+$num_reservas,$reserva->getTour()->getRuta()->getAforo())==false){
                $this->addFlash('Aforo',"No puedes reservar mas personas que el aforo");
            }else{
                $this->addFlash('Error',"Acceso denegado");
            }
        }else if ($form->isSubmitted()){
            $errores=$val->validate($reserva);
        }

        return $this->render('infoTour.html.twig', [
            'last_username' => $lastUsername,
            'error'=>$error,
            'tour'=>$tour,
            'items'=>$itemstour,
            'form'=>$form->createView(),
            'errores'=>$errores
        ]);
    }

    
    public static function validarAforo($numeroPersonas,$aforoPermitido)
    {
        // Implementa tu lógica de validación de aforo aquí
        // Puedes comparar $numeroPersonas con el aforo permitido y devolver true o false según sea necesario
        return $numeroPersonas <= $aforoPermitido;
    }



}
