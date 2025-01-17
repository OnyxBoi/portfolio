<?php

namespace App\Controller;

use App\Repository\ProjetRepository;
use App\Repository\FormationRepository;
use App\Repository\ExperienceRepository;
use App\Repository\HobbyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function accueil(
        ProjetRepository $projetRepository, 
        FormationRepository $formationRepository, 
        ExperienceRepository $experienceRepository, 
        HobbyRepository $hobbyRepository
    )
    {
        $projets = $projetRepository->createQueryBuilder('p')
            ->leftJoin('p.technologies', 't') 
            ->addSelect('t') 
            ->getQuery()
            ->getResult();

        $formations = $formationRepository->findAll();
        $experiences = $experienceRepository->findAll();
        $centresInterets = $hobbyRepository->findAll();

        return $this->render('accueil/index.html.twig', [
            'projets' => $projets,
            'formations' => $formations,
            'experiences' => $experiences,
            'centresInterets' => $centresInterets,
        ]);
    }
}
