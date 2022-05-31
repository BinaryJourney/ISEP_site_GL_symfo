<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileUpdateFormType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,
                             EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/infos/edit/{id}", name="app_profile_register_edit")
     */
    public function edit(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher,
                         EntityManagerInterface $entityManager): Response
    {
        $passwordHolder = $user->getPassword();
        $user->setPassword("");
        $form = $this->createForm(ProfileUpdateFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            /** @var User $user */
            $user = $form->getData();
            if(!$user->getPassword() == "")
            {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $this->addFlash('success', "Mot de passe mis à jour avec succès! Let's go !");

            } else {
                $user->setPassword($passwordHolder);
            }

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', "Informations personnelles mises à jour ! Vous êtes maintenant quelqu'un d'autre !");

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_profile_infos');
        }

        return $this->render('registration/edit.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

}
