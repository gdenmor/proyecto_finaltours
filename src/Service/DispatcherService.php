<?php
    namespace App\Service;

use App\Event\CorreoEvent;
use App\Event\EventoCorreo;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


    class DispatcherService{
        public function __construct(private EventDispatcherInterface $dispatcher,private Security $userInterface){
            $this->dispatcher=$dispatcher;
            $this->userInterface=$userInterface;
        }
        public function lanzaEvento(){
            $usuario=$this->userInterface->getUser();
            $evento=new CorreoEvent($usuario);
            $this->dispatcher->dispatch($evento);
        }

    }