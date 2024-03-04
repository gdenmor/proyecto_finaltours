<?php
    namespace App\Service;

use App\Repository\ReservaRepository;
use App\Repository\UserRepository;

    class ReservasService{
        public function __construct(private UserRepository $userrepo,private ReservaRepository $reservarepo){}
        public function reservasUsuario($id_user){
            $user=$this->userrepo->find($id_user);
            $reservas=$this->reservarepo->findBy(["Usuario"=>$user]);
            return $reservas;
        }
    }


?>