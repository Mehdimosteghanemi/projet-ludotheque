<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VerificationController extends AbstractController
{
    /**
     * @Route("/verification", name="verification")
     */
    public function index(): Response
    {
        return $this->render('connexion/verification.html.twig', [
            'controller_name' => 'VerificationController',
        ]);
    }
}
