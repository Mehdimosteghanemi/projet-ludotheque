<?php

namespace App\Controller\Backoffice;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/backoffice/utilisateur", name="backoffice_user_")
 */
class UserController extends AbstractController
{
    /**
     * Method for display all users
     * 
     * URL: /backoffice/utilisateur/
     * ROUTE: backoffice_user_index
     * 
     * @Route("/", name="index")
     */
    public function index(UserRepository $userRepository): Response
    {

        $user = $userRepository->findAll();

        return $this->render('backoffice/user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'title' => 'Utilisateur',
            'classRoute' => 'user',
            'headerArray' => ['nom/prénom', 'adresse', 'e-mail', 'role', 'statut', 'option'],
            'user' => $user
        ]);
    }


    /**
     * Method used to add a user
     * 
     * URL: /backoffice/utilisateur/ajout
     * ROUTE: backoffice_user_add
     *
     * @Route("/ajout", name="add")
     * 
     * @return Response
     */
    public function add(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();

        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() ) {

            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur ' . $form->get('firstname')->getData() . ' ' . $form->get('lastname')->getData() . ' a bien été ajouté.');
            
            return $this->redirectToRoute('backoffice_user_index');
        }

        return $this->renderForm('backoffice/user/add.html.twig', [
            'formView' => $form,
            'user' => $user,
            'title' => 'Ajout d\'un utilisateur',
            'classRoute' => 'user',
    
        ]);
    }

    /**
     * Method display the details of a user
     *
     * URL: /backoffice/utilisateur/show/{id}
     * ROUTE: backoffice_user_show
     * 
     * @Route("/bakcoffice/user/{id}", name="show", methods={"GET"})
     *
     * @return Response
     */
    public function show(User $user)
    {
        return $this->render('backoffice/user/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * Method used to update a user
     * 
     * URL: /backoffice/utilisateur/modification/{id}
     * ROUTE: backoffice_user_update
     * 
     * @Route("/modification/{id}", name="update")
     * 
     * @return Response
     */
    public function update(Request $request, User $user, UserPasswordHasherInterface $passwordHasher)
    {
        // $this->denyAccessUnlessGranted('USER_EDIT', $user, "Vous ne passerez paaaaaaaaaas !!");

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('modify', 'L\'utilisateur ' . $form->get('firstname')->getData() . ' ' . $form->get('lastname')->getData() . ' a bien été modifié.');

            return $this->redirectToRoute('backoffice_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/user/update.html.twig', [
            'user' => $user,
            'formView' => $form,
            'title' => 'Edition d\'un utilisateur',
            'classRoute' => 'user',
        ]);
    }


    /**
     * Method used for delere a user
     * 
     * URL: /backoffice/utilisateur/{id}/suppression
     * ROUTE: backoffice_user_delete
     * 
     * @Route("/{id}/suppression", name="delete", methods={"POST"})
     *
     * @return Response
     */
    public function delete(Request $request, User $user)
    {
        
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'L\'utilisateur ' . $user->getFirstname() . ' ' . $user->getLastname() . ' a bien été supprimé.');
        } else {
            $this->addFlash('error', 'Une erreur est survenue la suppression n\'a pas eu lieux.');
        }

        return $this->redirectToRoute('backoffice_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
