<?php

namespace App\Controller\apiexternal;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiexternalController extends AbstractController
{

    /**
     * 
     * @Route("/search/tet", name="front_article_search_all", methods={"GET"})
     */
    public function FunctionName()
    {
        $url = 'http://api.icndb.com/jokes/random/3?limitTo=[nerdy]&escape=javascript';
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $rawResponse = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);
        $response = json_decode($rawResponse, true);

        dd($response);
    }
}