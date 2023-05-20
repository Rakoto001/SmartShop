<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function createQuery()
    {
       return $this->createQueryBuilder('c');
    }
    /**
     * find all articles by ceteg id
     */
    public function findAllArticlesByCategId($id)
    {

        $results = $this->createQuery()
                      ->leftjoin('c.articles', 'a')
                      ->addSelect('a')
                      ->where('a.category= :id')
                      ->setParameter('id', $id)
                      ->getQuery()
                      ->getArrayResult();




// dd($query->getArrayResult());




        // /** erreur concernant ID */
        // $connexion = $this->getEntityManager()->getConnection();
        // $sql = "
        // SELECT DISTINCT * FROM `articles`   JOIN category 
        // ON articles.category_id=category.id 
        // WHERE category.id=$id
        // ";

        // $stmt = $connexion->prepare($sql);
        // $resultSet = $stmt->executeQuery()->fetchAllAssociative();
        // dd($resultSet);
        /** deprecié sur dbal */
        // $statement = $connexion->prepare($sql);
        // $statement->execute();
        // $results = $statement->fetchAllAssociative();
        /** nouvelle approche */
        // $results = $connexion->executeQuery($sql)->fetchAll();

        return $results;
    }
    
    public function findAllActif($_status)
    {
        // $query = $this->getEntityManager()->getConnection();
        // $sql = "
        // SELECT * FROM category WHERE category.status=$_status";
        // $statement = $query->prepare($sql);
        // $statement->execute();
        // $results = $statement->fetchAll();

        return  $qb = $this->createQuery()

                  ->andWhere('c.status = :status')
                  ->setParameter('status', $_status)
                  ->getQuery()
                  ->getResult();
        
    }

    /**
     * set the limit based on datatable params
     */
    public function setLimit($limit, $offset)
    {
        $qb = $this->createQuery()
                   ->setFirstResult($offset)
                   ->setMaxResults($limit);

        return $qb;
    }

    /**
     * make seearc if key search exists
     */
    public function makeSearch($params, $query)
    {
    
        if (isset($params['search']['value']) && !empty($params['search']['value'])) {
            $paramsToSearch = $params['search']['value'];

            $qb = $query->where('c.description LIKE :params')
                         ->setParameter('params', '%'.$paramsToSearch);

                return $qb;
        }

        return $query;
        
    }

     /**
     * make order by
     */
    public function makeOrder($_query)
    {
        $query = $_query->orderBy('c.id', 'ASC');

        return $query;
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
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
    public function findOneBySomeField($value): ?Category
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
