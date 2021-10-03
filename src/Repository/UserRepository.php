<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function createQuery()
    {

        return $this->createQueryBuilder('u');
    }

    /**
     * set the maxResult to 10 by the criterias
     */
    public function setLimit($limit, $offset)
    {

        $query = $this->createQuery()
                    ->setMaxResults($limit)
                    ->setFirstResult($offset);
        
        return $query;
        
    }

    /**
     * make order by
     */
    public function makeOrder($_query)
    {
        $query = $_query->orderBy('u.id', 'ASC');

        return $query;
    }

    /**
     * make search if key exists
     */
    public function makeSearch($_parametters, $_query)
    {
        // dd($_parametters['search']);
        if (array_key_exists('search',$_parametters) && !empty($_parametters['search']['value'])) {
            $result = $_query->where('u.username LIKE :username')
                             ->setParameter('username', $_parametters['search']['value'].'%');

            return $result;
        }

        return $_query;
    }

    public function findUserAndRole($_id)
    {
        $connexion = $this->getEntityManager()->getConnection();
        $sql = "
        SELECT * FROM `user` 
        JOIN roles on roles.users_id=user.id  
        WHERE user.id =$_id
        
        ";
        $statement = $connexion->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();
        dd($results);
    }

    /**
     * find all articles created by user id
     */
    public function findArticleByUser($_id)
    {
        // $connexion = $this->getEntityManager()->getConnection();
        // $sql = "
        // SELECT * FROM `articles` 
        // JOIN user on articles.created_by_id=user.id
        // WHERE articles.created_by_id =$_id
        // ";
        // $statement = $connexion->prepare($sql);
        // $statement->execute();
        // $results = $statement->fetchObject('articles');

        $entityManager = $this->getEntityManager();
        $query= $entityManager->createQuery(
                                                'SELECT a FROM App\Entity\Articles a 
                                                 JOIN App\Entity\User u 
                                                 WHERE a.createdBy=u.id 
                                                 AND a.createdBy = :id')
                              ->setParameter('id', $_id)
                              ->getResult();

        return $query;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
