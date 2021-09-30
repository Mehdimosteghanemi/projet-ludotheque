<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/coffre", name="chest_")
 */
class ChestController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(Security $security): Response
    {
        return $this->render('chest/index.html.twig', [
            'controller_name' => 'ChestController',
            'user' => $security->getUser(),
        ]);
    }

    /**
     * Method to update the status command.
     * Use the form post with checkbox.
     * @Route("/", name="update", methods={"POST"})
     */
    public function update(Security $security, OrderRepository $orderRepository): Response
    {
        // variable who check if there are changement or not
        $oneWasChecked = false;

        $user = $security->getUser();
        // loop in every data on superglobal post (the form send the game id in key and if it was selected(checked) or not in value)
        foreach ($_POST as $gameId => $checked) {
            // if it was checked we search the order whith the id game and user, we used almost the status argument if the user have already take the same game in the past 
            if ($checked) {
                // findBy give the result in array (we know they cant have two game whith status 1 for the same user we take the first and unique key with [0]])
                $order = $orderRepository->findBy(['games' => $gameId, 'users' => $user, "status" => 1])[0];
                // checked if is not null
                if ($order){
                    // pass the status to 2 (status for send) and create the command date
                    $order->setStatus(2);
                    $order->setDateCommand(new DateTimeImmutable());
                    // call the manager to put it on the database
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($order);
                    $manager->flush();
                    // we put one in database, so the form has been checked
                    $oneWasChecked = true;
                }
            }
        }

        // if it's checked, stock message and redirect if not the same but the message is error message
        if ($oneWasChecked) {
            $this->addFlash('success', "La commande est en bien prise en compte");
            return $this->render('chest/index.html.twig', [
                'controller_name' => 'ChestController',
                'user' => $security->getUser(),
            ]);
        } else {
            $this->addFlash('error', "La commande était vide");
            return $this->render('chest/index.html.twig', [
                'controller_name' => 'ChestController',
                'user' => $security->getUser(),
            ]);
        }     
    }
}
