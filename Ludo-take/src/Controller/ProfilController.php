<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
     * @Route("/profil", name="profil")
     */

class ProfilController extends AbstractController
{
    
      /**
     * Method used to display the whole list
     * 
     * URL : /profil/  NOM : index
     * 
     * @Route("/", name="index")
     * 
     */
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
}
