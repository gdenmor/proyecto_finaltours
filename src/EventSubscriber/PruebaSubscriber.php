<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\CorreoEvent;
use App\Event\EventoCorreo;
use App\Service\Correo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class PruebaSubscriber implements EventSubscriberInterface{

    public function __construct(private Correo $mailer,private Security $security,private EntityManagerInterface $entityManagerInterface){

    }
    public static function getSubscribedEvents(){
        return [
            CorreoEvent::class=> 'mandaCorreo',
        ];
    }

    public function mandaCorreo(CorreoEvent $event){
        $user=$this->security->getUser();

        if ($user) {
            $userdb = $this->entityManagerInterface->getRepository(User::class)->findOneBy(['username' => $user->getUsername()]);

            // Verifica si se encontró el usuario en la base de datos
            if ($userdb instanceof User) {
                // Ahora puedes enviar el correo al usuario autenticado
                $this->mailer->EnviaCorreo("Mensaje para el usuario logueado", $userdb->getEmail(), 'Asunto del correo');
            } else {
                // Manejar el caso donde el usuario no se encuentra en la base de datos
                // Por ejemplo, puedes loguear o lanzar una excepción
            }
        }
    }
}