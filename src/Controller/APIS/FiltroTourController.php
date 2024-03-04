<?php

namespace App\Controller\APIS;

use App\Entity\Ruta;
use App\Entity\Tour;
use App\Repository\ItemRepository;
use App\Repository\RutaRepository;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FiltroTourController extends AbstractController
{
    private Security $security;
    public function __construct(Security $security){
        $this->security = $security;
    }
    /*#[Route('/filtrotours/{nombre}', name: 'app_filtro_tour',methods:["GET"])]
    public function index(Request $request,string $nombre,TourRepository $toursrepo,RutaRepository $rutarepo,UserRepository $userrepo): JsonResponse
    {
        $tours = $toursrepo->findByLocalidadName($nombre);
    
        if ($tours) {
            $data = [];
            
            foreach ($tours as $tour) {
                $data[] = [
                    'titulo'=>$tour->getTitulo(),
                    'estado'=>$tour->getEstado(),
                    'numplazas'=>$tour->getNumplazas(),
                    'horario'=>$tour->getHorario(),
                    'ruta'=>$tour->getRuta(),
                    'guia'=>$tour->getGuia()
                ];
            }

            return $this->json($data, 200);
        } else {
            return $this->json(['message' => 'No existen localidades'], 300);
        }
    }*/
    #[Route('/addruta', name: 'add_ruta', methods: ['POST'])]
    public function addRuta(ValidatorInterface $validatorInterface,ItemRepository $itemRepository,Request $request, RutaRepository $rutaRepository,EntityManagerInterface $entity)
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $json= json_decode($request->get('json'),true);
        $ruta=new Ruta();
        $ruta->setTitulo($json['Titulo']);
        $ruta->setDescripcion($json['Descripcion']);
        $ruta->setAforo($json['Aforo']);
        $ruta->setFechaInicio(new DateTime($json['FechaInicio']));
        $ruta->setFechaFin(new DateTime($json['FechaFin']));
        $ruta->setPuntoEncuentro($json['punto_encuentro']);
        $items=$json['Items'];
        for ($i=0;$i<count($items);$i++){
            $item=$itemRepository->find($items[$i]);
            $ruta->addItem($item);
        }

        $public = $this->getParameter('kernel.project_dir');

        if (move_uploaded_file($_FILES['imagen']['tmp_name'],$public.'/public/css/imagenes/'. $_FILES['imagen']['name'])){
            $ruta->setFoto($_FILES['imagen']['name']);
        }


        if (count($validatorInterface->validate($ruta))==0) {

            $entity->persist($ruta);
            $entity->flush();

            return $this->json(['id' => $ruta->getId()], 201);
        }else{
            return $this->json($validatorInterface->validate($ruta), 400);
        }
    }

    #[Route('/updateruta/{id}', name: 'update_ruta', methods: ['POST'])]
    public function updateRuta(ValidatorInterface $validatorInterface,$id,ItemRepository $itemRepository,Request $request, RutaRepository $rutaRepository,EntityManagerInterface $entity){
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $json= json_decode($request->get('json'),true);
        $ruta=$rutaRepository->find($id);
        $titulo=$json['Titulo'];
        $ruta->setTitulo($titulo);
        $ruta->setDescripcion($json['Descripcion']);
        $ruta->setAforo($json['Aforo']);
        $ruta->setFechaInicio(new DateTime($json['FechaInicio']));
        $ruta->setFechaFin(new DateTime($json['FechaFin']));
        $ruta->setPuntoEncuentro($json['punto_encuentro']);
        $items=$json['Items'];
        for ($i=0;$i<count($items);$i++){
            $item=$itemRepository->find($items[$i]);
            $ruta->addItem($item);
        }

        $public = $this->getParameter('kernel.project_dir');

        $imagenFile = $request->files->get('imagen');

        if ($imagenFile) {
            // Procede a manejar el archivo
            $imagenFileName = md5(uniqid()) . '.' . $imagenFile->guessExtension();
            $imagenFile->move($public . '/public/css/imagenes/', $imagenFileName);
            $ruta->setFoto($imagenFileName);
        }

        if (count($validatorInterface->validate($ruta))==0) {

            $entity->persist($ruta);
            $entity->flush();

            return $this->json(['id' => $ruta->getId()], 201);
        }else{
            return $this->json($validatorInterface->validate($ruta), 400);
        }
    }

    #[Route('/addprogramacion', name: 'add_programacion', methods: ['POST'])]
    public function programacion(Request $request,RutaRepository $rutaRepository,EntityManagerInterface $entityManagerInterface):JsonResponse{
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $obj=$request->getContent();
        $si=json_decode($obj,true);
        $idRuta="";
        foreach ($si as $item) {
            $idRuta = $item['idRuta'];
        }
        $ruta=$rutaRepository->find($idRuta);
        $ruta->setProgramacion(json_decode($obj));
        $entityManagerInterface->persist($ruta);
        $entityManagerInterface->flush();
        $context = ['groups' => ['Default']];
        $entityManagerInterface->getConfiguration()->getDefaultQueryHints()['doctrine.serializer.groups'] = $context;
        return $this->json(['message' => 'Programacion added successfully'],200);
    }

    #[Route('/pasalista/{id_tour}',name: 'pasalista')]
    public function pasalista($id_tour,Request $request,TourRepository $toursrepo,RutaRepository $rutarepo,UserRepository $userrepo,EntityManagerInterface $entityManagerInterface): JsonResponse{
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $obj=$request->getContent();
        $si=json_decode($obj,true);
        $tour=$toursrepo->find($id_tour);
        $tour->setNumPersonasAsisten($si['num_personas']);
        $entityManagerInterface->persist($tour);
        $entityManagerInterface->flush();
        $context = ['groups' => ['Default']];
        $entityManagerInterface->getConfiguration()->getDefaultQueryHints()['doctrine.serializer.groups'] = $context;
        return $this->json(['message' => 'Se pasó lista correctamente'],200);
    }

    #[Route('/addtours', name: 'add_tour', methods: ['POST'])]
    public function addTours(UserRepository $userRepository,Request $request,RutaRepository $rutaRepository,EntityManagerInterface $entityManagerInterface){
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $obj=$request->getContent();
        $si=json_decode($obj,true);
        foreach ($si as $item){
            $hora=$item['hora'];
            $fechaInicio=new DateTime($item['fechaInicio'].$hora);
            $fechaFin=new DateTime($item['fechaFin'].$hora);
            $dias=$item['diasSemana'];
            $idRuta=$item['idRuta'];
            $idGuia=$item['idGuia'];
            $intervalo = new DateInterval('P1D');
            $periodo=new DatePeriod($fechaInicio,$intervalo,$fechaFin);
            foreach ($periodo as $date){
                $dayOfWeek = strtolower($date->format('l'));

                if (in_array($dayOfWeek, $dias)) {
                    $tour = new Tour();
                    $ruta = $rutaRepository->find($idRuta);

                    $tour->setRuta($ruta);
                    $tour->setGuia($userRepository->find($idGuia));
                    $tour->setFecha(new DateTime($date->format('Y-m-d')));
                    $tour->setHora(new DateTime($date->format('H:i')));

                    $entityManagerInterface->persist($tour);
                }
            }
        }

        $entityManagerInterface->flush();

        return $this->json(["Exito"=>"Los tours fueron creados con exito"]);
    }

    
}