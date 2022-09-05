<?php

namespace App\Controller;

use App\Repository\ColectionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ColectionController extends AbstractController
{
    #[Route('/administrateur/collections', name: 'administrateur_colection_list')]
    public function list(ColectionRepository $repo): Response
    {
        $colevtions = $repo->findAll();
        return $this->render('colection/index.html.twig', [
            'controller_name' => 'ColectionController',
        ]);
    }
}
