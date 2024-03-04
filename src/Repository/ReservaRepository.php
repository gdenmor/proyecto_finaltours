<?php

namespace App\Repository;

use App\Entity\Reserva;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDO;
use PDOException;

/**
 * @extends ServiceEntityRepository<Reserva>
 *
 * @method Reserva|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reserva|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reserva[]    findAll()
 * @method Reserva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reserva::class);
    }

    public static function reservasusuario($id_usuario){
        $Usuarios=[];
        try {
            // Crear una conexión PDO
            $conn = new PDO("mysql:host=127.0.0.1:3306;dbname=proyecto_final;charset=utf8mb4", "root", "Root");
        
            // Consulta SQL
            $sql = "SELECT u.* FROM reserva r INNER JOIN user u ON u.id = r.usuario_id WHERE r.usuario_id = :usuario_id";
        
            // Preparar la declaración SQL
            $resultado = $conn->prepare($sql);
        
            // Bind de los parámetros
            $resultado->bindParam(':usuario_id', $id_usuario, PDO::PARAM_INT);
        
            // Ejecutar la consulta
            $resultado->execute();
            while ($tuplas=$resultado->fetch(PDO::FETCH_OBJ)) {
                $user=new User();
                $user->setEmail($email=$tuplas->email);
                $user->setRoles((array)$roles=$tuplas->roles);
                $user->setPassword($password=$tuplas->password);
                $user->setTelefono($telefono=$tuplas->telefono);
                $user->setApellido1($ap1=$tuplas->apellido1);
                $user->setApellido2($ap2=$tuplas->apellido2);
                $user->setUsername($username=$tuplas->username);
                $user->setFoto($foto=$tuplas->foto);
                
                $Usuarios[]=$user;
            }
        
        
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $Usuarios;
    }

    public static function reservatour($id_tour){
        $reservas=0;
        try {
            // Crear una conexión PDO
            $conn = new PDO("mysql:host=127.0.0.1:3306;dbname=proyecto_final;charset=utf8mb4", "root", "Root");
        
            // Consulta SQL
            $sql = "select count(r.id) as Reservas from tour t inner join reserva r on t.id=r.tour_id where r.tour_id=:tour;";
        
            // Preparar la declaración SQL
            $resultado = $conn->prepare($sql);
        
            // Bind de los parámetros
            $resultado->bindParam(':tour', $id_tour, PDO::PARAM_INT);
        
            // Ejecutar la consulta
            $resultado->execute();
            while ($tuplas=$resultado->fetch(PDO::FETCH_OBJ)) {
                $reservas=$tuplas->Reservas;
            }
        
        
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $reservas;
    }

    public static function numpersonas($id_tour){
        $reservas=0;
        try {
            // Crear una conexión PDO
            $conn = new PDO("mysql:host=127.0.0.1:3306;dbname=proyecto_final;charset=utf8mb4", "root", "Root");
        
            // Consulta SQL
            $sql = "select SUM(r.num_personas) as SumaPersonas from tour t inner join reserva r on t.id=r.tour_id where r.tour_id=:tour;";
        
            // Preparar la declaración SQL
            $resultado = $conn->prepare($sql);
        
            // Bind de los parámetros
            $resultado->bindParam(':tour', $id_tour, PDO::PARAM_INT);
        
            // Ejecutar la consulta
            $resultado->execute();
            while ($tuplas=$resultado->fetch(PDO::FETCH_OBJ)) {
                $reservas=$tuplas->SumaPersonas;
            }
        
        
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $reservas;
    }

    public function valoraciontour($id_tour){
        $media=0;
        try {
            // Crear una conexión PDO
            $conn = new PDO("mysql:host=127.0.0.1:3306;dbname=proyecto_final;charset=utf8mb4", "root", "Root");
        
            // Consulta SQL
            $sql = "select avg(v.estrellas) as media from reserva r inner join valoracion v on r.id=v.reserva_id inner join tour t on t.id=r.tour_id where t.id=:tour;";
        
            // Preparar la declaración SQL
            $resultado = $conn->prepare($sql);
        
            // Bind de los parámetros
            $resultado->bindParam(':tour', $id_tour, PDO::PARAM_INT);
        
            // Ejecutar la consulta
            $resultado->execute();
            while ($tuplas=$resultado->fetch(PDO::FETCH_OBJ)) {
                $media=$tuplas->media;
            }
        
        
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $media;
    }

//    /**
//     * @return Reserva[] Returns an array of Reserva objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reserva
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
