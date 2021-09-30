<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/order", name="order_")
 */
class OrderController extends AbstractController
{
    /**
     * Methode to create a Order between a Game and an User
     * @Route("/{id<\d+>}", name="new")
     */
    public function new(int $id, Game $game, Security $security, OrderRepository $orderRepository): Response
    {
        // if ($game->getAvailable() > 0) {
            // if a game is on the chest dont create a new order
            if ($orderRepository->findBy(['games' => $game->getId(), 'users' => $security->getUser()->getId()])) {
                return $this->redirectToRoute('chest_index');
            } else {
                // it's notfound we can create
                $order = new Order;
                $order->setGames($game);
                $order->setUsers($security->getUser());
                $order->setStatus(1);

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($order);
                $manager->flush();
                return $this->redirectToRoute('chest_index');
            }
            // if (isset($security->getUser()->))
        // }   
    }

    /**
     * Methode to create a Order between a Game and an User
     * @Route("/delete/{id<\d+>}", name="delete")
     */
    public function delete(int $id, Game $game, Security $security, OrderRepository $orderRepository): Response
    {
        // if ($game->getAvailable() > 0) {
            $order = $orderRepository->findBy(['games' => $game->getId(), 'users' => $security->getUser()->getId()]);
            // if a game is on the chest delete him
            if ($order) {
                $manager = $this->getDoctrine()->getManager();
                $manager->remove($order[0]);
                $manager->flush();
                return $this->redirectToRoute('chest_index');
            } else {
                // it's not just redirect
                
                return $this->redirectToRoute('chest_index');
            }
            // if (isset($security->getUser()->))
        // }   
    }
}
