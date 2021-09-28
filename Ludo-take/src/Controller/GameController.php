<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/jeux", name="game_")
 * 
 * @IsGranted("ROLE_USER")
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
    public function index(GameRepository $gameRepository): Response
    {

        $gamesList = $gameRepository->findAll();

        return $this->render('game/index.html.twig', [
            'gamesList' => $gamesList
        ]);
    }
}
