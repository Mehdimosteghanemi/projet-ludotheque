<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/404/", name="not_found_")
 */

class NotFoundController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('not_found/index.html.twig', [
            'controller_name' => 'NotFoundController',          
        ]);
    }
}
