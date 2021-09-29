<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Order;
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
    public function new(int $id, Game $game, Security $security): Response
    {
        // if ($game->getAvailable() > 0) {
            $order = new Order;
            $order->setGames($game);
            $order->setUsers($security->getUser());
            $order->setStatus(1);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($order);
            $manager->flush();
            return $this->render('order/new.html.twig', [
                'controller_name' => 'OrderController',
            ]);
        // }
        
    }
}
