<?php

namespace App\Controller\front\branche;

use App\Entity\Branche;
use Elastica\Query\BoolQuery;
use Doctrine\ORM\EntityManager;
use App\Services\BracheServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BrancheController extends AbstractController
{

    public $container;
    public $brancheService;
    public $fosFinder;

    public function __construct(ContainerInterface $_container, BracheServices $_brancheService, $fosFinder) {
        $this->container = $_container;
        $this->brancheService = $_brancheService;
        $this->fosFinder = $fosFinder;
    }

    /**
    * @Route("/autoCompletionMarque", name="autoCompletionMarque", methods={"POST", "OPTIONS"})
    */
    public function autoCompleteBranche(RepositoryManagerInterface $finder)
    {
        $esFinder = $finder->getRepository(Branche::class);

        // $fosManager = $this->get('fos_elastica.manager');


        
        // $finder = $this->get('fos_elastica.finder.app.book');
    $searchTerm = 'congo';

    $searchQuery = new \Elastica\Query\QueryString();
    $searchQuery->setParam('query', $searchTerm);
    $searchQuery->setDefaultOperator('AND');
    $searchQuery->setParam('fields', array(
        'country',
        'place',
        'code',
    ));

    $books = $esFinder->find($searchQuery);

    dd($books);

    return array(
        'entities' => $books
    );




        return true;
    }

   /**
    * @Route("/sql/test", name="autoCompletionMarque", methods={"GET", "OPTIONS"})
    */
    public function testSQL(RepositoryManagerInterface $finder)
    {
        $esFinder = $finder->getRepository(Branche::class);

        
        $index = 'fos_elastica.manager.orm'; // consulté dans php bin/console debug:container fos_elastica
        $_critere = 'Alb';

        $queryString = new \Elastica\Query\QueryString();
        $sSearch = '*' . str_replace(['-', '+'], ' ', $_critere) . '*';
        
        $queryString->setQuery($sSearch);
        $queryString->setBoost(7.0);
        //$boolQuery = new \Elastica\Query\BoolQuery();
        //$boolQuery->addMust($queryString);
       
        $query = new \Elastica\Query;
        $query->setQuery($queryString);
        $query->setSize(9999);
        // dd($query);
      
      try {
        $aObject = $this->fosFinder->find($query);
          
      } catch (\Throwable $th) {
          dd($th);
      }
        dump($aObject);
        die;
        if (!empty($aObject)) {
            return $aObject;
        }














        return true;

    }


}