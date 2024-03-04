<?php
    namespace App\Service;

use App\Controller\PdfController;
use Dompdf\Dompdf;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

    class PdfGenerator{
        public function __construct(private ContainerBagInterface $params,private Environment $entorno){}
        public function generaPDF($data){
            $html =  $this->entorno->render('pdf/index.html.twig', $data);
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->render();
            return $dompdf;
        }

        public function imageToBase64($path) {
            $path1 = $path;
            $type = pathinfo($path1, PATHINFO_EXTENSION);
            $data = file_get_contents($path1);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            return $base64;
        }

        public function obtainsrc($ruta){
            return $this->imageToBase64($this->params->get('kernel.project_dir') . '/'.$ruta);
        }
    }


?>