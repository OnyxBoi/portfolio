<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Technologie;
use App\Form\TechnologieType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FormationRepository;
use App\Repository\HobbyRepository;
use App\Repository\ProjetRepository;
use App\Repository\ExperienceRepository;
use App\Repository\TechnologieRepository;

class TechnologieController extends AbstractController
{
    #[Route('/technologie', name: 'app_technologie')]
    public function index(): Response
    {
        return $this->render('technologie/index.html.twig', [
            'controller_name' => 'TechnologieController',
        ]);
    }


    #[Route('/technologie/ajouter', name: 'app_technologie_add')]
    public function addTechnologie(Request $request, EntityManagerInterface $entityManager): Response
    {
        $techno = new Technologie();
        $form = $this->createForm(TechnologieType::class, $techno);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($techno);
            $entityManager->flush();

            return $this->redirectToRoute('app_gestion');
        }

        return $this->render('gestion/index.html.twig', [
            'form_technologie' => $form->createView(),
        ]);
    }

    #[Route('/technologie/{id}/modifier', name: 'app_technologie_edit')]
    public function editTechnologie(
        Technologie $technologie,
        Request $request,
        EntityManagerInterface $entityManager,
        ProjetRepository $projetRepository,
        FormationRepository $formationRepository,
        ExperienceRepository $experienceRepository,
        TechnologieRepository $technologieRepository,
        HobbyRepository $hobbyRepository,
        int $id
    ): Response {
        $form = $this->createForm(TechnologieType::class, $technologie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'technologie modifié avec succès.');
            return $this->redirectToRoute('app_gestion');
        }

        return $this->render('gestion/index.html.twig', [
            'form_formation' => $form->createView(),
            'form_projet' => $form->createView(),
            'form_experience' => $form->createView(),
            'form_technologie' => $form->createView(),
            'form_hobby' => $form->createView(),
            'projets' => $projetRepository->findAll(),
            'formations' => $formationRepository->findAll(),
            'experiences' => $experienceRepository->findAll(),
            'technologies' => $technologieRepository->findAll(),
            'centresInterets' => $hobbyRepository->findAll(),
            'edit_technologie_id' => $id,
            'edit_projet_id' => null, 
            'edit_formation_id' => null, 
            'edit_experience_id' => null, 
            'edit_hobby_id' => null, 

        ]);
    }


    #[Route('/technologie/{id}/supprimer', name: 'app_technologie_delete')]
    public function deleteTechnologie(Technologie $techno, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($techno);
        $entityManager->flush();

        return $this->redirectToRoute('app_gestion');
    }

}
