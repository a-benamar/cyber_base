<?php

namespace App\Controller;

use App\Entity\Animateur;
use App\Form\AnimateurType;
use App\Repository\AnimateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/animateur')] //Cette annotation indique que tout URI utilisé par les méthodes

class AnimateurController extends AbstractController
{
    #[IsGranted('ROLE_ANIMATEUR')]
    #[Route('/', name: 'app_animateur_index', methods: ['GET'])]
    public function index(AnimateurRepository $animateurRepository): Response
    {
        $genre = $animateurRepository->findAnimateurByGenreFemme();
        return $this->render('animateur/index.html.twig', [
            'animateurs' => $animateurRepository->findAll(),
            'genre' => $genre,
        ]);
    }

    #[IsGranted('ROLE_SUPER_ANIMATEUR')]
    #[Route('/new', name: 'app_animateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,
     UserPasswordHasherInterface $userPasswordHasher): Response
     // return une reponse http
    {
        $animateur = new Animateur();
        // je  relié le form a l'objet $animateur
        $form = $this->createForm(AnimateurType::class, $animateur);
        // je demande au form d analyser le request $request(recupere les donnees de formulaire)
        $form->handleRequest($request);
        // si le form est soumis et tous les champs de form est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // je recupere le password de mon form je le hash et je le stock dans le variable hash
            $password_hashed = $userPasswordHasher->hashPassword($animateur, $form->get('plainPassword')->getData());
            // modifer le password avec le mot pass hashe
            $animateur->setPassword($password_hashed);
           // prepare pour sauvgarder
           $entityManager->persist($animateur);
           //je sauvgarde dans la bdd
           $entityManager->flush();

            $this->addFlash(
                'success',
                "Animateur ajouté avec succès ."
             );
            return $this->redirectToRoute('app_animateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('animateur/new.html.twig', [
            'animateur' => $animateur,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_SUPER_ANIMATEUR')]
    #[Route('/{id}', name: 'app_animateur_show', methods: ['GET'])]
    public function show(Animateur $animateur): Response
    {
        return $this->render('animateur/show.html.twig', [
            'animateur' => $animateur,
        ]);
    }

    #[IsGranted('ROLE_ANIMATEUR')]
    #[Route('/{id}/edit', name: 'app_animateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Animateur $animateur, AnimateurRepository $animateurRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(AnimateurType::class, $animateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $animateur->setPassword(
            $userPasswordHasher->hashPassword(
                    $animateur,
                    $form->get('plainPassword')->getData()
                )
            );
            $animateurRepository->add($animateur);
            $this->addFlash(
                'success',
                "Animateur modifié avec succès ."
             );
            return $this->redirectToRoute('app_animateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('animateur/edit.html.twig', [
            'animateur' => $animateur,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_SUPER_ANIMATEUR')]
    #[Route('/{id}', name: 'app_animateur_delete', methods: ['POST'])]
    public function delete(Request $request, Animateur $animateur, AnimateurRepository $animateurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animateur->getId(), $request->request->get('_token'))) {
            $animateurRepository->remove($animateur);
        }

        return $this->redirectToRoute('app_animateur_index', [], Response::HTTP_SEE_OTHER);
    }



}
