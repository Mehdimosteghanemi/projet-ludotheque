<?php

namespace App\Controller\Backoffice;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/backoffice/categorie", name="backoffice_category_", requirements={"id":"\d+"})
 */
class CategoryController extends AbstractController
{
    /**
     * Method for display all categories
     * 
     * URL: /backoffice/categorie/
     * ROUTE: /backoffice_category_index
     * 
     * @Route("/", name="index")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {

        return $this->render('backoffice/category/index.html.twig', [
            'category' => $categoryRepository->findAll()
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

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'La catégorie ' . $category->getName() . ' a bien été créée.');

            return $this->redirectToRoute('backoffice_category_index');
        }

        return $this->render('backoffice/category/add.html.twig', [
            'formView' => $form->createView()
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
    public function update(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La catégorie ' . $category->getName() . ' a bien été modifié.');

            return $this->redirectToRoute('backoffice_category_index');
        }

        return $this->render('backoffice/category/update.html.twig', [
            'category' => $category,
            'formView' => $form->createView()
        ]);
    }

    /**
     * Method used to delete a category
     * 
     *  URL: /backoffice/categorie/suppression/{id}
     *  ROUTE: backoffice_category_delete
     * 
     * @Route("/delete/{id}", name="delete")
     *
     * @return Response
     */
    public function delete(Category $category)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $this->addFlash('info', 'La catégorie ' . $category->getName() . ' a bien été supprimé.');

        return $this->redirectToRoute('backoffice_category_index');
    }
}
