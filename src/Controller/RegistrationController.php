<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    //#[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $name = $form->get('name')->getData();
            $user->setName($name);

            $plainPassword = $form->get('plainPassword')->get('first')->getData(); // Premier champ
            $plainPasswordConfirmation = $form->get('plainPassword')->get('second')->getData(); // Deuxième champ

            if ($plainPassword !== $plainPasswordConfirmation) {
                // Ajouter une erreur si les mots de passe ne correspondent pas
                $form->get('plainPassword')->addError(new FormError('Les mots de passe ne correspondent pas.'));
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                ]);
            }

            // Hashage du mot de passe
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Assigner le rôle
            $user->setRoles([$form->get('isAdmin')->getData() ? 'ROLE_ADMIN' : 'ROLE_USER']);

            // Sauvegarder l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}


