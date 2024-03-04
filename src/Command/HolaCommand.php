<?php
namespace App\Command;

use App\Service\Correo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'send:email')]
class HolaCommand extends Command{
    public function __construct(private Correo $correo){
        parent::__construct();
    }
    protected function configure():void{
        $this
            //->addArgument('email', InputArgument::REQUIRED, 'Correo destinatario')
            //->addOption('email',InputArgument::REQUIRED,'Correo destinatario');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output):int{

        $helper = $this->getHelper('question');


        $nameQuestion = new Question('Introduzca su correo: '. PHP_EOL);
        $name = $helper->ask($input, $output, $nameQuestion);


        $this->correo->EnviaCorreo('Prueba correo',$name,'Comando');

        $output->writeln('Correo enviado correctamente');

        return Command::SUCCESS;
    }
}