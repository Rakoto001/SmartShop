<?php


namespace App\Services;


class FosElasticaService
{
    private $fosFinder;

    public function __construct($fosFinder) {
        $this->fosFinder = $fosFinder;

    }

    public function makeESearch(array $params)
    {

        $_critere = $params['article-search'];
        
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
          dd( ['dans FosESService'. $th]);
        }
       

        if (!empty($aObject)) {

            return $aObject;
        }

        return null;
        
    }
}