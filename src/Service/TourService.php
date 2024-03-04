<?php
    namespace App\Service;

use App\Repository\ReservaRepository;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

    class TourService{
        public function __construct(private TourRepository $tourrepo,private EntityManagerInterface $entity
        ,private ReservaRepository $reservarepo,private Correo $correo,private UserRepository $userrepo){}
        public function cancelaTour($id){
            $tour=$this->tourrepo->find($id);
            $tour->setEstado("CANCELADO");
            $reservas=$this->reservarepo->findBy(["tour"=>$tour]);
            if (count($reservas)){
                $usuariosNotificados = [];
                for ($i=0;$i<count($reservas);$i++){
                    $user=$reservas[$i]->getUsuario();
                    if (!in_array($user->getId(), $usuariosNotificados)) {
                        $this->correo->EnviaCorreo("La ruta ha sido cancelada. 
                                Por lo tanto, ya habrá nuevos tours próximamente en estas fechas", $user->getEmail(), "Cancelación de la ruta");
            
                        // Agrega el ID del usuario a la lista de usuarios notificados
                        $usuariosNotificados[] = $user->getId();
                    }
                }
                $id_guia=$tour->getGuia();
                if ($id_guia==null){

                }else{
                    $guia=$this->userrepo->find($tour->getGuia()->getId());
                    if ($guia){
                        $this->correo->EnviaCorreo("La ruta ha sido cancelada. 
                        Por lo tanto, ya habrá nuevos tours proximamente en estas fechas",$guia->getEmail(),"Cancelación de la ruta");
                    }
                }
                $this->entity->persist($tour);
                $this->entity->flush();
            }
        }

        public function verToursAsignados($id_guia){
            $guia=$this->userrepo->find($id_guia);
            $tours=$this->tourrepo->findBy(["Guia"=>$guia]);
            return $tours;
        }
            
    }

?>