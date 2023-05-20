<?php

namespace App\Controller\front\branche;

use App\Entity\Branche;
use Elastica\Query\BoolQuery;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BrancheController extends Controller
{

    public $container;

    public function __construct(ContainerInterface $_container) {
        $this->container = $_container;
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
}