<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(GameRepository $gameRepository, CategoryRepository $categoryRepository): Response
    {
        $categoryList = $categoryRepository->findAll();
        shuffle($categoryList);
      
        $category =$categoryRepository->findAll();
        shuffle($category);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'games' => $gameRepository->findBy([], ['created_at' => 'DESC']),
            'category' => $category,
            'categoryList' => $categoryList,
        ]);
    }

    /**
     * @Route("/", name="registration", methods={"POST"})
     */
    public function redirectToRegistration(): RedirectResponse
    {
        $email = $_POST['email'];
        return $this->redirectToRoute('register', ['email' => $email]);
    }
}
