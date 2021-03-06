<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

//    public function search($term, $order = 'asc', $limit = 20, $offset = 0)
//    {
//        $qb = $this
//            ->createQueryBuilder('a')
//            ->select('a')
//            ->orderBy('a.title', $order)
//        ;
//
//        if ($term) {
//            $qb
//                ->where('a.title LIKE ?1')
//                ->setParameter(1, '%'.$term.'%')
//            ;
//        }
//
//        return $this->paginate($qb, $limit, $offset);
//    }

//    public function createAskedOrderedByNewestQueryBuilder(): QueryBuilder
//    {
//        return $this->addIsAskedQueryBuilder()
//            ->orderBy('q.askedAt', 'DESC')
//            ->leftJoin('q.questionTags', 'question_tag')
//            ->innerJoin('question_tag.tag', 'tag')
//            ->addSelect('question_tag', 'tag')
//            ;
//    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
