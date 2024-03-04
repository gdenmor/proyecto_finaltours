<?php
    namespace App\Service;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Email;

    class Correo{
        private string $admin;
        public function __construct(private MailerInterface $mailer,string $admin){
            $this->admin=$admin;
        }

        public function EnviaCorreo($mensaje,$destinatario,$sujeto): bool
        {

            $email = (new Email())
                ->from($this->admin)
                ->to($destinatario)
                ->subject($sujeto)
                ->html($mensaje);

            $this->mailer->send($email);

            return true;
        }
    }




?>