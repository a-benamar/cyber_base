<?php

namespace App\Controller;

use App\Entity\Animateur;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[IsGranted('ROLE_SUPER_ANIMATEUR')]
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator): Response
    {
        $user = new Animateur();
        // on relie le form a l'objet $user
        $form = $this->createForm(RegistrationFormType::class, $user);
        // demande au form de analyse le request $request
        $form->handleRequest($request);
        // si le form est soumis et tous les champs de form est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // je recupere le password de mon form et je le stock dans l variable hash
            $hash = $userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData());
            // modifer le password avec le mot pass hashe
            $user->setPassword($hash);
            // prepare a sauvgarder
            $entityManager->persist($user);
            //sauvgarder
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),// mon formulaire
        ]);
    }
}
