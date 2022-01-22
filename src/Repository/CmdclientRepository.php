<?php

namespace App\Repository;

use App\Entity\Cmdclient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cmdclient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cmdclient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cmdclient[]    findAll()
 * @method Cmdclient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmdclientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cmdclient::class);
    }

    // /**
    //  * @return Cmdclient[] Returns an array of Cmdclient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cmdclient
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    
    public function selectcmdclient(int $category)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM cmdclient c
            WHERE c.idclient = :price
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['price' => $category]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();    
    }
}
