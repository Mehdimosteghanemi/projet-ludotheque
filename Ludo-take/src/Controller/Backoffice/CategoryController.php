<?php

namespace App\Controller\Backoffice;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/backoffice/categorie", name="backoffice_category_", requirements={"id"="\d+"})
 */
class CategoryController extends AbstractController
{
    /**
     * Method for display all categories
     * 
     * URL: /backoffice/categorie/
     * ROUTE: backoffice_category_index
     * 
     * @Route("/", name="index")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {

        $category = $categoryRepository->findAll();


        return $this->render('backoffice/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'title' => 'Catégories',
            'classRoute' => 'category',
            'headerArray' => ['nom'],
            'category' => $category
        ]);
    }


    /**
     * Method used to add a category
     *
     * @Route("/ajout", name="add")
     * 
     * @return Response
     */
    public function add(Request $request)
    {

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isCsrfTokenValid('add', $request->request->get('token'))) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();

                $this->addFlash('success', 'La catégorie ' . $category->getName() . ' a bien été créée.');
            }

            return $this->redirectToRoute('backoffice_category_index');
        }

        return $this->render('backoffice/category/add.html.twig', [
            'formView' => $form->createView(),
            'classRoute' => 'category',
            'title' => 'Ajout d\'une catégorie',
        ]);
    }

    /**
     * Method display the details of a category
     * 
     * URL: /backoffice/categorie/{id}
     * ROUTE: :backoffice_category_show
     * 
     * @Route("/{id}")
     *
     * @return Response
     */
    public function show(int $id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        if (!$category){
            $this->addFlash('error', 'Le jeu ' . $id . ' n\'existe pas.');
        }

        return $this->render('backoffice/category/show.html.twig', [
            'category' => $category
        ]);

    }

    /**
     * Method used to update a category
     * 
     * URL: /backoffice/categorie/modification/{id}
     * ROUTE: :backoffice_category_update
     *
     * @Route("/modification/{id}", name="update")
     * 
     * @return Response
     */
    public function update(Request $request, ?Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isCsrfTokenValid('add', $request->request->get('token'))) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('modify', 'La catégorie ' . $category->getName() . ' a bien été modifié.');
            }

            return $this->redirectToRoute('backoffice_category_index');
        }


        return $this->render('backoffice/category/update.html.twig', [
            'category' => $category,
            'formView' => $form->createView(),
            'classRoute' => 'category',
            'title' => 'Edition d\'une catégorie',
        ]);
    }

    /**
     * Method used to link a category with game
     * 
     * URL: /backoffice/categorie/lien/{id}
     * ROUTE: :backoffice_category_link
     *
     * @Route("/lien/{id}", name="link", methods={"GET"})
     * 
     * @return Response
     */
    public function link(Category $category, GameRepository $gameRepository)
    {

        return $this->render('backoffice/category/link.html.twig', [
            'category' => $category,
            'classRoute' => 'category',
            'title' => $category->getName(),
            'gameList' => $gameRepository->findAll(),
        ]);
    }

    /**
     * Method used to link a category with game
     * 
     * URL: /backoffice/categorie/lien/{id}
     * ROUTE: :backoffice_category_link
     *
     * @Route("/lien/{id}", name="link_post", methods={"POST"})
     * 
     * @return Response
     */
    public function createLink(Request $request ,Category $category, GameRepository $gameRepository, CategoryRepository $categoryRepository)
    {
        if ($this->isCsrfTokenValid('csurf'.$category->getId(), $request->request->get('token'))) {
            foreach ($_POST as $gameId => $value) {
                if ($value === "on") {
                    $category->addGame($gameRepository->find($gameId));
                } elseif ($value === "off") {
                    $category->removeGame($gameRepository->find($gameId));
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();
            }

            $this->addFlash('success', 'La catégorie ' . $category->getName() . ' a bien été lié aux jeux.');
        } else {
            $this->addFlash('error', 'Une erreur est survenue la modification n\'a pas eu lieux.');
        }

        return $this->redirectToRoute('backoffice_category_index', [
            'categories' => $categoryRepository->findAll(),
            'title' => 'Catégories',
            'classRoute' => 'category',
            'headerArray' => ['nom', 'option',],
            'category' => $category
        ]);
    }

    /**
     * Method used to delete a category
     * 
     *  URL: /backoffice/categorie/{id}/suppression
     *  ROUTE: backoffice_category_delete
     * 
     * @Route("/{id}/suppression", name="delete", methods={"POST"})
     *
     * @return Response
     */
    public function delete(Request $request, ?Category $category)

    {
        
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();


            $this->addFlash('success', 'La catégorie ' . $category->getName() . ' a bien été supprimé.');
        } else {
            $this->addFlash('error', 'Une erreur est survenue la suppression n\'a pas eu lieux.');
        }

        return $this->redirectToRoute('backoffice_category_index');
    }
}
