<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Course>
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    //    /**
    //     * @return Course[] Returns an array of Course objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Course
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    //DQL
    /*
    public function findLastCourses(int $duration = 2) {

        $dql = "SELECT c FROM App\Entity\Course c
                WHERE c.duration > :duration
                ORDER BY c.dateCreated DESC";

        $em = $this->getEntityManager();
        $query = $em->createQuery($dql);
        $query->setMaxResults(5);
        $query->setParameter('duration', $duration);
        return $query->getResult();
    }
     *
     */

    // QueryBuilder
    public function findLastCourses(int $duration = 2): Paginator
    {

        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder
            ->addSelect('ca')
            ->leftJoin('c.category', 'ca')
            ->addSelect('t')
            ->leftJoin('c.trainers', 't')
            ->andWhere('c.duration > :duration')
            ->andWhere('c.published = true')
            ->setParameter('duration', $duration)
            ->addOrderBy('c.dateCreated', 'DESC')
            ->addOrderBy('c.name', 'ASC');

        $query = $queryBuilder->getQuery();
        $query->setMaxResults(20);
        return new Paginator($query);
    }
}
