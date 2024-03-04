<?php
    namespace App\Service;

use App\Entity\Informe;
use App\Form\InformeType;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

    class GuiaService{
        public function __construct(private UserRepository $userrepo,private TourRepository $tourrepo,
        private Request $request,private EntityManagerInterface $entity){}
        public function toursGuia($id){
            $user=$this->userrepo->find($id);
            $toursguia=$this->tourrepo->findBy(["Guia"=>$user]);
            return $toursguia;
        }

        public function creaInforme($form){
            $data=$form->getData();
            $this->entity->persist($data);
            $this->entity->flush();
        }
    }


?>