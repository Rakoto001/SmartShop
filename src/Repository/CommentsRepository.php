<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

     /**
     * find all artiles
     */
    public function findAllArticles()
    {

        $connexion = $this->getEntityManager()->getConnection();
        $sql = "
        SELECT * FROM `articles` INNER JOIN comments ON comments.article_id=articles.id
        ";
        $statement = $connexion->prepare($sql);
         $statement->execute();
         $results = $statement->fetchAll();
        // $qb = $this->createQuery()
        //            ->leftJoin('a.comments', 'categ')
        //            ->addSelect('categ')
        //         //    ->OrderBy('a.id', 'ASC')
        //            ->getQuery()
        //            ->getResult();  
        dd($results);          

        // return $qb;

    }

    public function findCommmentByCriteria($_userId, $_articleId)
    {
        $connexion = $this->getEntityManager()->getConnection();
        $sql = "
        SELECT comments.content FROM `comments` 
        JOIN user ON comments.author_id=user.id
        JOIN articles ON comments.article_id=articles.id
        WHERE author_id=$_userId AND article_id =$_articleId
                ";
        $statement = $connexion->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();
        // dd($results);
         return $results;
        }


    // /**
    //  * @return Comments[] Returns an array of Comments objects
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
    public function findOneBySomeField($value): ?Comments
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
