<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\HobbyType;
use App\Entity\Hobby;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProjetRepository;
use App\Repository\FormationRepository;
use App\Repository\ExperienceRepository;
use App\Repository\TechnologieRepository;
use App\Repository\HobbyRepository;

class HobbyController extends AbstractController
{
    #[Route('/centre/interet', name: 'app_hobby')]
    public function index(): Response
    {
        return $this->render('hobby/index.html.twig', [
            'controller_name' => 'HobbyController',
        ]);
    }


    #[Route('/hobby/ajouter', name: 'app_hobby_add')]
    public function addHobby(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hobby = new Hobby();
        $form = $this->createForm(HobbyType::class, $hobby);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($hobby);
            $entityManager->flush();

            return $this->redirectToRoute('app_gestion');
        }

        return $this->render('gestion/index.html.twig', [
            'form_hobby' => $form->createView(),
        ]);
    }

    #[Route('/hobby/{id}/modifier', name: 'app_hobby_edit')]
    public function editHobby(
        Hobby $hobby,
        Request $request,
        EntityManagerInterface $entityManager,
        ProjetRepository $projetRepository,
        FormationRepository $formationRepository,
        ExperienceRepository $experienceRepository,
        TechnologieRepository $technologieRepository,
        HobbyRepository $hobbyRepository,
        int $id
    ): Response {
        $form = $this->createForm(HobbyType::class, $hobby);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Centre d interet modifié avec succès.');
            return $this->redirectToRoute('app_gestion');
        }

        return $this->render('gestion/index.html.twig', [
            'form_projet' => $form->createView(),
            'form_formation' => $form->createView(),
            'form_experience' => $form->createView(),
            'form_technologie' => $form->createView(),
            'form_hobby' => $form->createView(),
            'projets' => $projetRepository->findAll(),
            'formations' => $formationRepository->findAll(),
            'experiences' => $experienceRepository->findAll(),
            'technologies' => $technologieRepository->findAll(),
            'centresInterets' => $hobbyRepository->findAll(),
            'edit_hobby_id' => $id,
            'edit_projet_id' => null, 
            'edit_technologie_id' => null, 
            'edit_formation_id' => null, 
            'edit_experience_id' => null, 
        ]);
    }

    #[Route('/hobby/{id}/supprimer', name: 'app_hobby_delete')]
    public function deleteHobby(Hobby $hobby, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($hobby);
        $entityManager->flush();

        return $this->redirectToRoute('app_gestion');
    }

}
