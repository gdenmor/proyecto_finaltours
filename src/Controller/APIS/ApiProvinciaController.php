<?php

namespace App\Controller\APIS;

use App\Entity\Provincia;
use App\Repository\ProvinciaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiProvinciaController extends AbstractController
{
    private Security $security;
    public function __construct(Security $security){
        $this->security=$security;
    }
    
    #[Route('/verprovincia/{id}', name: 'app_provincia_id',methods:["GET"])]
    public function index($id,ProvinciaRepository $provinciarepo)
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $provincia=$provinciarepo->findOneBy(["id"=>$id]);
        if ($provincia){
            $obj=[
                'id'=>$provincia->getId(),
                'nombre'=>$provincia->getNombre()
            ];
            return $this->json($obj,200);
        }else{
            return $this->json(['message' => 'No existe una provincia con ese ID'], 404);
        }
        
    }

    #[Route('/verprovincias', name: 'provincias',methods:["GET"])]
    public function all(ProvinciaRepository $provinciarepo,Request $request)
    {
        $provincias = $provinciarepo->findAll();
    
        if ($provincias) {
            $data = [];
            
            foreach ($provincias as $provincia) {
                $data[] = [
                    'id' => $provincia->getId(),
                    'nombre' => $provincia->getNombre()
                ];
            }

            return $this->json($data, 200);
        } else {
            return $this->json(['message' => 'No existen localidades'], 404);
        }
        
    }

    #[Route('/provincia/crear', name: 'add_provincia', methods: ['POST'])]
    public function addUsuario(Request $request, ProvinciaRepository $provinciaRepository,EntityManagerInterface $entity): JsonResponse
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $data = json_decode($request->getContent(), true);
        // Crear una nueva entidad de Usuario
        $provincia=new Provincia();
        $provincia->setNombre($data["nombre"]);

        // Guardar el usuario en la base de datos
        $entity->persist($provincia);
        $entity->flush();

        return $this->json(['message' => 'Provincia creada con éxito'], 201);
    }

    #[Route('/provincia/editar/{id}', name: 'update_provincia', methods: ['PUT'])]
    public function updateUsuarioByID(Request $request, $id, ProvinciaRepository $localidadRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $provincia= $localidadRepository->find($id);
        $data = json_decode($request->getContent(), true);

        // Actualiza los campos del usuario
        $provincia->setNombre($data['nombre']);
        

        $entityManager->persist($provincia);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Provincia actualizada con éxito']);
    }

    #[Route('/provincia/eliminar/{id}', name: 'delete_provincia',methods:["DELETE"])]
    public function removeLocal(ProvinciaRepository $localidadRepository, EntityManagerInterface $entity,$id): JsonResponse
    {
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json(['message' => 'Acceso no autorizado'], 401);
        }
        $provincia = $localidadRepository->find($id);

        if (!$provincia) {
            return $this->json(['message' => 'No existe una provincia con ese ID'], 404);
        }

        $entity->remove($provincia);
        $entity->flush();

        return $this->json(['message' => 'Provincia eliminada con éxito'], 204);
    }
}
