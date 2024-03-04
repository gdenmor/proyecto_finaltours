<?php

namespace App\Controller\APIS;

use App\Entity\Localidad;
use App\Repository\LocalidadRepository;
use App\Repository\ProvinciaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ApiLocalidadController extends AbstractController
{
    private Security $security;
    public function __construct(Security $security){
        $this->security = $security;
    }
    #[Route('/verlocalidad/{id}', name: 'app_localidad_id',methods:["GET"])]
    public function index(LocalidadRepository $repo,$id,ProvinciaRepository $provinciarepo)
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $localidad=$repo->findOneBy(["id"=>$id]);
        $id_provincia=$localidad->getProvincia()->getId();
        if ($localidad){
            $provincia=$provinciarepo->findOneBy(["id"=>$id_provincia]);
            $obj=[
                'id'=>$provincia->getId(),
                'nombre'=>$provincia->getNombre()
            ];
            $data=[
                'id'=>$localidad->getId(),
                'nombre'=>$localidad->getNombre(),
                'provincia'=>$obj
            ];
            return $this->json($data,200);
        }else{
            return $this->json(['message' => 'No existe un usuario con ese ID'], 404);
        }
        
    }

    #[Route('/verlocalidades', name: 'localidades',methods:["GET"])]
    public function localidades(LocalidadRepository $repo,Request $request,ProvinciaRepository $provinciarepo)
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $localidades = $repo->findAll();
    
        if ($localidades) {
            $data = [];
            
            foreach ($localidades as $localidad) {
                $id_provincia = $localidad->getProvincia()->getId();
                $provincia = $provinciarepo->findOneBy(["id" => $id_provincia]);

                $data[] = [
                    'id' => $localidad->getId(),
                    'nombre' => $localidad->getNombre(),
                    'provincia' => [
                        'id' => $provincia->getId(),
                        'nombre' => $provincia->getNombre()
                    ]
                ];
            }

            return $this->json($data, 200);
        } else {
            return $this->json(['message' => 'No existen localidades'], 404);
        }
        
    }

    #[Route('/localidad/crear', name: 'add_localidad', methods: ['POST'])]
    public function addUsuario(Request $request, LocalidadRepository $usuarioRepository,EntityManagerInterface $entity): JsonResponse
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $data = json_decode($request->getContent(), true);

        // Obtener los datos de la localidad (id, email, isVerified) desde $data
        $nombre = $data['nombre'];
        $provincia = $data['provincia'];

        // Crear una nueva entidad de Usuario
        $localidad=new Localidad();
        $localidad->setNombre($nombre);
        $localidad->setProvincia($provincia);

        // Guardar el usuario en la base de datos
        $entity->persist($localidad);
        $entity->flush();

        return $this->json(['message' => 'Usuario creado'], 201);
    }

    #[Route('/localidad/editar/{id}', name: 'update_localidad', methods: ['PUT'])]
    public function updateUsuarioByID(Request $request, $id, LocalidadRepository $localidadRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $localidad= $localidadRepository->find($id);
        $data = json_decode($request->getContent(), true);

        // Actualiza los campos del usuario
        $localidad->setNombre($data['nombre']);
        $localidad->setProvincia($data['provincia']);
        

        $entityManager->persist($localidad);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Localidad actualizado con éxito']);
    }

    #[Route('/localidad/eliminar/{id}', name: 'delete_localidad',methods:["DELETE"])]
    public function removeLocal(LocalidadRepository $localidadRepository, EntityManagerInterface $entity,$id): JsonResponse
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $localidad = $localidadRepository->find($id);

        if (!$localidad) {
            return $this->json(['message' => 'No existe un usuario con ese ID'], 404);
        }

        $entity->remove($localidad);
        $entity->flush();

        return $this->json(['message' => 'Localidad eliminada'], 204);
    }
}