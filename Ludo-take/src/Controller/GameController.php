<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/jeux", name="game_")
 * 
 */
class GameController extends AbstractController
{ 

    /**
     * Method used to display the whole list
     * 
     * URL : /jeux/  NOM : game_index
     * 
     * @Route("/", name="index")
     * 
     */
    public function index(GameRepository $gameRepository, Request $request, PaginatorInterface $paginatorInterface): Response
    {

        $data = $gameRepository->findBy([], ['created_at' => 'DESC']);

        // $data = $this->getDoctrine()->getRepository(GameRepository::class)->findBy([], ['created_at' => 'DESC']);

        $gamesList = $paginatorInterface->paginate(
            $data, // Query containing the data to paginate (here our articles)
            $request->query->getInt('page', 1), // Current page number, in to url, 1 if null
            7 // Result number per page
        );

        return $this->render('game/index.html.twig', [
            'gamesList' => $gamesList
        ]);
    }

}
