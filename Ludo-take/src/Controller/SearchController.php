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
        
        $results = $gameRepository->searchGameByName($query);

        if (!$results) {

            // throw $this->createNotFoundException("Le jeu $query n'existe pas.");
            $this->addFlash('error', 'Le jeu ' . $query . ' n\'existe pas.');

            return $this->redirectToRoute('game_index');

        } else {

            $searchResult = $paginatorInterface->paginate(
                $results,
                $request->query->getInt('page', 1),
                5
            );

        }
        
        return $this->render('game/index.html.twig', [
            'gamesList' => $searchResult,
            'query' => $query
        ]);
    }  
}
