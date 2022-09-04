<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperateurController extends AbstractController
{
    #[Route('/operateur', name: 'app_operateur')]
    public function index(): Response
    {
        return $this->render('operateur/index.html.twig', [
            'controller_name' => 'OperateurController',
        ]);
    }
}
