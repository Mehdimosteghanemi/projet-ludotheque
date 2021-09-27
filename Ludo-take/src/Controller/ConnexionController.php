<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
     * @Route("/connexion", name="log_")
     */

class ConnexionController extends AbstractController
{
    /**
     * @Route("/", name="in")
     */
    public function index(): Response
    {
        return $this->render('connexion/index.html.twig',[
            'controller_name' => 'ConnexionController',
        ]);
    }
}


