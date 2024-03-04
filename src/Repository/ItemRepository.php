<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDO;

/**
 * @extends ServiceEntityRepository<Item>
 *
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function ItemsdeTour($id_tour,ItemRepository $userRepository,LocalidadRepository $localidadRepository)
    {
        $conexion = new PDO("mysql:host=127.0.0.1:3306;dbname=proyecto_final;charset=utf8mb4", "root", "Root");
        $resultado = $conexion->prepare("select i.* from item_ruta ir inner join ruta r on r.id=ir.ruta_id inner join tour t on t.ruta_id=r.id inner join item i on ir.item_id=i.id where t.id=:tour;");
        $resultado->bindParam(":tour",$id_tour,PDO::PARAM_INT);
        $resultado->execute();

        $Items = [];

        $i = 0;


        while ($tuplas = $resultado->fetch(PDO::FETCH_OBJ)) {
            $item=new Item();
            $item->setId($tuplas->id);
            $item->setTitulo($tuplas->titulo);
            $item->setDescripcion($tuplas->descripcion);
            $item->setFoto($tuplas->foto);
            $localidad=$localidadRepository->find($tuplas->localidad_id);
            $item->setLocalidad($localidad);
            $Items[]=$item;
            $i++;
        }

        return $Items;
    }

    public function ItemsdeTourPorProvincia(ItemRepository $userRepository,LocalidadRepository $localidadRepository,$provincia)
    {
        $conexion = new PDO("mysql:host=127.0.0.1:3306;dbname=proyecto_final;charset=utf8mb4", "root", "Root");
        $resultado = $conexion->prepare("select distinct i.* from item_ruta ir 
        inner join ruta r on r.id=ir.ruta_id 
        inner join tour t on t.ruta_id=r.id 
        inner join item i on ir.item_id=i.id
        inner join localidad l on l.id=i.localidad_id
        inner join provincia p on p.id=l.provincia_id
        where p.nombre=:provincia;");
        $resultado->bindParam(":provincia",$provincia,PDO::PARAM_STR);
        $resultado->execute();

        $Items = [];

        $i = 0;


        while ($tuplas = $resultado->fetch(PDO::FETCH_OBJ)) {
            $item=new Item();
            $item->setId($tuplas->id);
            $item->setTitulo($tuplas->titulo);
            $item->setDescripcion($tuplas->descripcion);
            $item->setFoto($tuplas->foto);
            $localidad=$localidadRepository->find($tuplas->localidad_id);
            $item->setLocalidad($localidad);
            $Items[]=$item;
            $i++;
        }

        return $Items;
    }

    public function ItemsNoAsignados(ItemRepository $userRepository,LocalidadRepository $localidadRepository)
    {
        $conexion = new PDO("mysql:host=127.0.0.1:3306;dbname=proyecto_final;charset=utf8mb4", "root", "Root");
        $resultado = $conexion->prepare("SELECT i.*
        FROM item i
        LEFT JOIN item_ruta ir ON i.id = ir.item_id
        WHERE ir.item_id IS NULL;");
        $resultado->execute();

        $Items = [];

        $i = 0;


        while ($tuplas = $resultado->fetch(PDO::FETCH_OBJ)) {
            $item=new Item();
            $item->setId($tuplas->id);
            $item->setTitulo($tuplas->titulo);
            $item->setDescripcion($tuplas->descripcion);
            $item->setFoto($tuplas->foto);
            $localidad=$localidadRepository->find($tuplas->localidad_id);
            $item->setLocalidad($localidad);
            $Items[]=$item;
            $i++;
        }

        return $Items;
    }


//    /**
//     * @return Item[] Returns an array of Item objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Item
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
