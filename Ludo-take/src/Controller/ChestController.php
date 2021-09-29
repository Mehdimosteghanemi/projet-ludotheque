<?php

namespace App\Controller;

use App\Repository\OrderRepository;
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
     * @Route("/", name="index")
     */
    public function index(Security $security): Response
    {
        return $this->render('chest/index.html.twig', [
            'controller_name' => 'ChestController',
            'user' => $security->getUser(),
        ]);
    }
}
