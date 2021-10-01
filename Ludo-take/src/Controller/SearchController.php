<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/recherche", name="search_")
 */
class SearchController extends AbstractController
{
    /**
     * Displays the search result
     * 
     * URL : /recherche
     * 
     * @Route("/", name="index")
     */
    public function index(Request $request, GameRepository $gameRepository): Response
    {

        $query = $request->query->get('search');

        $results = $gameRepository->searchGameByName($query);

        // dd($results);

        return $this->render('search/index.html.twig', [
            'results' => $results,
            'query' => $query
        ]);
    }
}
