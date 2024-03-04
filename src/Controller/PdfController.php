<?php

namespace App\Controller;

use App\Service\PdfGenerator;
use Dompdf\Dompdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;

class PdfController extends AbstractController
{
    #[Route('/pdf', name: 'app_pdf')]
    public function index(PdfGenerator $generadorPDF):Response
    {
        $data = [
            'imageSrc'  => $generadorPDF->obtainsrc('public/css/imagenes/descarga.jpg'),
            'name'         => 'John Doe',
            'address'      => 'USA',
            'mobileNumber' => '000000000',
            'email'        => 'john.doe@email.com'
        ];
        $dompdf=$generadorPDF->generaPDF($data);
        return new Response (
            $dompdf->stream('resume', ["Attachment" => false]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }

    
}
