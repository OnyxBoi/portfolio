<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\FormationRepository;
use App\Repository\HobbyRepository;
use App\Repository\ProjetRepository;
use App\Repository\ExperienceRepository;
use App\Repository\TechnologieRepository;

class ProjetsController extends AbstractController
{
    #[Route('/projets', name: 'app_projets')]
    public function index(): Response
    {
        return $this->render('projets/index.html.twig', [
            'controller_name' => 'ProjetsController',
        ]);
    }



    #[Route('/projet/ajouter', name: 'app_projet_add')]
    public function addProjet(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projet);
            $entityManager->flush();

            return $this->redirectToRoute('app_gestion');
        }

        return $this->render('gestion/index.html.twig', [
            'form_projet' => $form->createView(),
        ]);
    }

    #[Route('/projet/{id}/modifier', name: 'app_projet_edit')]
    public function editProjet(
        Projet $projet,
        Request $request,
        EntityManagerInterface $entityManager,
        ProjetRepository $projetRepository,
        FormationRepository $formationRepository,
        ExperienceRepository $experienceRepository,
        TechnologieRepository $technologieRepository,
        HobbyRepository $hobbyRepository,
        int $id
    ): Response {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Projet modifié avec succès.');
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
            'edit_projet_id' => $id,
            'edit_technologie_id' => null, 
            'edit_formation_id' => null, 
            'edit_experience_id' => null, 
            'edit_hobby_id' => null, 

        ]);
    }


    #[Route('/projet/{id}/supprimer', name: 'app_projet_delete')]
    public function deleteProjet(Projet $projet, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($projet);
        $entityManager->flush();

        return $this->redirectToRoute('app_gestion');
    }

}
