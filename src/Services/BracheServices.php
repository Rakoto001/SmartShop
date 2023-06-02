<?php

namespace App\Services;

use Psr\Container\ContainerInterface;

class BracheServices {

    
    public $container;

    public function __construct(ContainerInterface $_container) {
        $this->container = $_container;
    }

    public function makeRequestElasicSearch($_index = null, $_services = null, $_nameParamsSearch = null)
    {
        global $kernel;
        $container = $kernel->getContainer()->get('fos_elastica.finder');
        dd($container);
        $index = 'fos_elastica.finder.branches'; // consulté dans php bin/console debug:container fos_elastica
        // $serviceElasticaPlan = $this->container->get('fos_elastica.finder.branches');


        $_critere = "Alb";

        
        $queryString = new \Elastica\Query\QueryString();
        $sSearch = '*' . str_replace(['-', '+'], ' ', $_critere) . '*';
        dump($serviceElasticaPlan);
        die;
    }

}