<?php

namespace App\Controller;

use App\Repository\TechnicienRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TechnicienController extends AbstractController
{
    #[Route('/technicien/techniciens', name: 'technicien_list')]
    public function index(TechnicienRepository $repo): Response
    {
        $techniciens = $repo->findAll();
        return $this->render('technicien/index.html.twig', [
            'controller_name' => 'TechnicienController',
        ]);
    }
}
