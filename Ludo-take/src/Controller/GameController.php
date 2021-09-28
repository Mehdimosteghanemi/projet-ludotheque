<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/jeux", name="game_")
 */
class GameController extends AbstractController
{
    /**
     * Methode who show a game by this slug
     * @Route("/{slug}", name="slug")
     */
    public function index(string $slug, GameRepository $gameRepository): Response
    {
        return $this->render('game/slug.html.twig', [
            'game' => $gameRepository->findBy(['slug' => $slug])[0],
            'one_page' => true,
        ]);
    }
}
