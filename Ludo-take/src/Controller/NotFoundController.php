<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class NotFoundController extends AbstractController
{
    /**
     * @Route("/{string<.*>}", name="not_found", priority="-10")
     */
    public function index(string $string): Response
    {
       

        return $this->render('not_found/index.html.twig', [
            'search' => $string,
            'one_page' => true,


        ]);

    }
}
