<?php

namespace App\Repository;

use App\Entity\Ruta;
use App\Entity\Tour;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Time;
use PDO;
use PDOException;

/**
 * @extends ServiceEntityRepository<Tour>
 *
 * @method Tour|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tour|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tour[]    findAll()
 * @method Tour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TourRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tour::class);
    }

    public function buscaToursByLocalidad($localidad,RutaRepository $reporuta,UserRepository $userRepository,$fecha_inicio,$fecha_fin)
    {
        $conexion = new PDO("mysql:host=127.0.0.1:3306;dbname=proyecto_final;charset=utf8mb4", "root", "Root");
        $resultado = $conexion->prepare("select t.* from ruta r inner join item_ruta ir on r.id=ir.ruta_id inner join tour t on t.ruta_id=r.id
        inner join item i on ir.item_id=i.id inner join localidad l on i.localidad_id=l.id where l.nombre=:localidad and t.fecha>=:fechainicio and t.fecha<=:fechafin;");
        $resultado->bindParam(":localidad",$localidad,PDO::PARAM_STR);
        $resultado->bindParam(":fechainicio",$fecha_inicio,PDO::PARAM_STR);
        $resultado->bindParam(":fechafin",$fecha_fin,PDO::PARAM_STR);
        $resultado->execute();

        $Tours = [];

        $i = 0;


        while ($tuplas = $resultado->fetch(PDO::FETCH_OBJ)) {
            $tour=new Tour();
            $tour->setId($tuplas->id);
            $ruta=$reporuta->find($tuplas->ruta_id);
            $tour->setRuta($ruta);
            $guia_id=$tuplas->guia_id;
            if ($guia_id==null){
                $tour->setGuia(null);
            }else{
                $tour->setGuia($userRepository->find($guia_id));
            }
            $tour->setFecha(new \DateTime($tuplas->fecha));
            $tour->setHora(new \DateTime($tuplas->hora));
            $Tours[]=$tour;
            $i++;
        }

        return $Tours;
    }

    public function ToursMejorValorados(RutaRepository $reporuta,UserRepository $userRepository)
    {
        $conexion = new PDO("mysql:host=127.0.0.1:3306;dbname=proyecto_final;charset=utf8mb4", "root", "Root");
        $resultado = $conexion->prepare("SELECT t.*
        FROM ruta r
        INNER JOIN tour t ON t.ruta_id = r.id
        INNER JOIN reserva re ON re.tour_id = t.id
        INNER JOIN valoracion v ON v.reserva_id = re.id
        where re.estado='ACEPTADO'
        GROUP BY t.id
        ORDER BY AVG(v.estrellas) ASC
        LIMIT 3;");
        $resultado->execute();

        $Tours = [];

        $i = 0;


        while ($tuplas = $resultado->fetch(PDO::FETCH_OBJ)) {
            $tour=new Tour();
            $tour->setId($tuplas->id);
            $ruta=$reporuta->find($tuplas->ruta_id);
            $tour->setRuta($ruta);
            $guia_id=$tuplas->guia_id;
            if ($guia_id==null){
                $tour->setGuia(null);
            }else{
                $tour->setGuia($userRepository->find($guia_id));
            }
            $tour->setFecha(new \DateTime($tuplas->fecha));
            $tour->setHora(new \DateTime($tuplas->hora));
            $Tours[]=$tour;
            $i++;
        }

        return $Tours;
    }


//    public function findOneBySomeField($value): ?Tour
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
