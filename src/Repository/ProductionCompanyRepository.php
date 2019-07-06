<?php

namespace App\Repository;

use App\Entity\ProductionCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductionCompany|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductionCompany|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductionCompany[]    findAll()
 * @method ProductionCompany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductionCompanyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductionCompany::class);
    }

    // /**
    //  * @return ProductionCompany[] Returns an array of ProductionCompany objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductionCompany
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
