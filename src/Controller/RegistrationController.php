<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\UserEditionValidation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,
            UserEditionValidation $u, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();
            $repeatPassword = $form->getData()->getRepeatPassword();
            
            if ($repeatPassword == null || $repeatPassword != $password) {
                return $this->render('register.html.twig', [
                    'errors' => 'Les mots de passe ne correspondent pas',
                    'registrationForm' => $form->createView(),
                ]);
            }
            if ($u->fieldIsValid('name', $user->getName())) {
                $u->reserveName($user->getName());
            } else {
                return $this->render('register.html.twig', [
                    'errors' => 'Ce nom n\'est pas disponible',
                    'registrationForm' => $form->createView(),
                ]);
            }
            
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user, $password
                )
            );

            $entityManager->persist($user->beforeFirstSave());
            $entityManager->flush();
            
            //TODO mettre un message flash
            
            return $this->redirectToRoute('root');
        }

        return $this->render('register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
