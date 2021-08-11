<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    public function createQuery()
    {
        return $this->createQueryBuilder('a');
    }

    /**
     * find all artiles
     */
    public function findAllArticles($_max)
    {

        $connexion = $this->getEntityManager()->getConnection();
        $sql = "
        SELECT * FROM `articles`
        WHERE articles.status = 1
        
        ";
        if($_max == false){
            $sql .= "LIMIT 3"; 
        }
        $statement = $connexion->prepare($sql);
         $statement->execute();
         $results = $statement->fetchAll();
        // $qb = $this->createQuery()
        //            ->leftJoin('a.comments', 'categ')
        //            ->addSelect('categ')
        //         //    ->OrderBy('a.id', 'ASC')
        //            ->getQuery()
        //            ->getResult();  

        
        return $results;

        // return $qb;

    }

    /**
     * find All comments by article id
     */
    public function findAllComments($id)
    {
        
        $query = $this->getEntityManager()->getConnection();
        $sql = "
        SELECT DISTINCT comments.content, user.username  
        FROM comments  
        JOIN articles ON comments.article_id=articles.id 
        JOIN user ON comments.author_id=user.id
        WHERE articles.id=$id";
        $statement = $query->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();
        
        return $results;
    }

    // /**
    //  * @return Articles[] Returns an array of Articles objects
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
    public function findOneBySomeField($value): ?Articles
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
