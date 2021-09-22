<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Form\UserFormTypeUserType;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\Goto_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription/etape-une", name="register-1")
     */
    public function newStep1(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User;

        $form = new UserFormTypeUserType();

        $form = $this->createForm(formStepOne::class, $user);

        if($form->isValid() ) {
            
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $response = $this->forward(newStep2, $user);

            return $response;
           
        }

        return $this->render('registration/register-1.html.twig', [
            'userForm' => $form->createView(),
            $user
        ]);
    }

    /**
     * @Route("/inscription/etape-deux", name="register-2")
     */
    public function newStep2(User $user, EntityManagerInterface $entityManager): Response
    {
        
        new formStep2($user);

        if(formStep2()->isSubmitted() && $form->isValid()) {
             }

        return $this->render('registration/register-1.html.twig', [
            'userForm1' => $form->createView(),
        ]);
    }
}
