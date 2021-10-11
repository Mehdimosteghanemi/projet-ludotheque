<?php

namespace App\Controller\Backoffice;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/backoffice/jeux", name="backoffice_game_", requirements={"id"="\d+"})
 */
class GameController extends AbstractController
{
    /**
     * Method for display all jeux
     * 
     * URL: /backoffice/jeux/
     * ROUTE: backoffice_game_index
     * 
     * @Route("/", name="index")
     */
    public function index(GameRepository $gameRepository): Response
    {

        $game = $gameRepository->findAll();


        return $this->render('backoffice/game/index.html.twig', [
            'games' => $gameRepository->findAll(),
            'title' => 'Jeux',
            'classRoute' => 'game',
            'headerArray' => ['nom', 'description', 'image', 'difficulté', 'joueurs', 'durée', 'stock'],
            'game' => $game
        ]);
    }


    /**
     * Method used to add a game
     *
     * @Route("/ajout", name="add")
     * 
     * @return Response
     */
    public function add(Request $request)
    {

        $game = new game();

        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isCsrfTokenValid('add', $request->request->get('token'))) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($game);
                $em->flush();

                $this->addFlash('success', 'Le jeu ' . $game->getName() . ' a bien été créée.');
            }

            return $this->redirectToRoute('backoffice_game_index');
        }

        return $this->render('backoffice/game/add.html.twig', [
            'formView' => $form->createView(),
            'classRoute' => 'game',
            'title' => 'Ajout d\'un nouveau jeu',
        ]);
    }

    /**
     * Method display the details of a game
     * 
     * URL: /backoffice/jeux/{id}
     * ROUTE: :backoffice_game_show
     * 
     * @Route("/{id}")
     *
     * @return Response
     */
    public function show(int $id, GameRepository $gameRepository)
    {
        $game = $gameRepository->find($id);

        if (!$game){
            $this->addFlash('error', 'Le jeu ' . $id . ' n\'existe pas.');
        }

        return $this->render('backoffice/game/show.html.twig', [
            'game' => $game
        ]);

    }

    /**
     * Method used to update a game
     * 
     * URL: /backoffice/jeux/modification/{id}
     * ROUTE: :backoffice_game_update
     *
     * @Route("/modification/{id}", name="update")
     * 
     * @return Response
     */
    public function update(Request $request, ?Game $game)
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isCsrfTokenValid('add', $request->request->get('token'))) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('modify', 'Le jeu ' . $game->getName() . ' a bien été modifié.');
            }

            return $this->redirectToRoute('backoffice_game_index');
        }


        return $this->render('backoffice/game/update.html.twig', [
            'game' => $game,
            'formView' => $form->createView(),
            'classRoute' => 'game',
            'title' => 'Edition d\'un jeu',
        ]);
    }

    /**
     * Method used to delete a game
     * 
     *  URL: /backoffice/jeux/{id}/suppression
     *  ROUTE: backoffice_game_delete
     * 
     * @Route("/{id}/suppression", name="delete", methods={"POST"})
     *
     * @return Response
     */
    public function delete(Request $request, ?Game $game)

    {
        
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($game);
            $em->flush();


            $this->addFlash('success', 'Le jeux ' . $game->getName() . ' a bien été supprimé.');
        } else {
            $this->addFlash('error', 'Une erreur est survenue la suppression n\'a pas eu lieux.');
        }

        return $this->redirectToRoute('backoffice_game_index');
    }
}
