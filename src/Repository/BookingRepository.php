<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    // /**
    //  * @return Booking[] Returns an array of Booking objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findBookingByUser($user){

        $query = $this->createQueryBuilder('b')
            ->leftJoin('b.user', 'user')
            ->addSelect('user')
            ->where('user.id = :user')
            ->setParameter('user', $user)
            ->getQuery();
        return $query->getResult();
    }
    public function findBookingByUserAnDate($user){

        $query = $this->createQueryBuilder('b')
            ->select('b')
            ->where('b.user = :user')
            ->andwhere('b.beginAt> :date')
            ->setParameter('user', $user)
            ->setParameter('date', new \DateTime('now'))
            ->getQuery();
        return $query->getResult();
    }
    public function findRangeDate($beginAt, $endAt, $terrain){

        $query = $this->createQueryBuilder('b')
            ->select('b')
            ->where(':begin BETWEEN b.beginAt AND b.endAt')
            ->orWhere(':end BETWEEN b.beginAt AND b.endAt')
            ->andWhere(':terrain = b.numterrain')
            ->setParameter('begin', $beginAt->modify('+1 min') )
            ->setParameter('end', $endAt->modify('-1 min'))
            ->setParameter('terrain', $terrain )
            ->getQuery();
        return $query->getResult();

    }


}
