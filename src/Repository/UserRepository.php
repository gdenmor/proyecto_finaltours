<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDO;
use PDOException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
* @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function muestraGuias(){
        $Usuarios=[];
        try {
            // Crear una conexión PDO
            $conn = new PDO("mysql:host=127.0.0.1:3306;dbname=proyecto_final;charset=utf8mb4", "root", "Root");
        
            // Consulta SQL
            $sql = "SELECT * FROM user WHERE roles LIKE '%ROLE_GUIA%';";
        
            // Preparar la declaración SQL
            $resultado = $conn->prepare($sql);
        
            // Ejecutar la consulta
            $resultado->execute();
            while ($tuplas=$resultado->fetch(PDO::FETCH_OBJ)) {
                $user=new User();
                $user->setID($tuplas->id);
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

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
