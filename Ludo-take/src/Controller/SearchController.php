<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(Request $request, GameRepository $gameRepository, PaginatorInterface $paginatorInterface): Response
    {

        $query = $request->query->get('search');
        
        // $pregQuery = preg_replace("/^(.){3,128}$/" ,$query);
        // dd($pregQuery);
        //    "/^[.]{3,128}$/"
        $results = $gameRepository->searchGameByName($query);

        $searchResult = $paginatorInterface->paginate(
            $results,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('search/index.html.twig', [
            'results' => $searchResult,
            'query' => $query
        ]);
    }  
}
