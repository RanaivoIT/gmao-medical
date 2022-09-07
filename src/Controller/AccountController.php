<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    #[Route('/', name: 'app')]
    public function index(): Response
    {
        return $this->redirectToRoute('operateur_login');
    }
    

    #[Route('/administrateur/login', name: 'administrateur_login')]
    public function a_login(AuthenticationUtils $utils): Response
    {
        if ($this->getUser() === null) {
            $error = $utils->getLastAuthenticationError();
            $email = $utils->getLastUsername();
    
            return $this->render('login.html.twig', [
                'hasError' => $error !== null,
                'email' => $email
            ]);

        }else {
            return $this->redirectToRoute('administrateur');
        }
    }
    #[Route('/technicien/login', name: 'technicien_login')]
    public function t_login(AuthenticationUtils $utils): Response
    {
        if ($this->getUser() === null) {
            $error = $utils->getLastAuthenticationError();
            $email = $utils->getLastUsername();
    
            return $this->render('login.html.twig', [
                'hasError' => $error !== null,
                'email' => $email
            ]);

        }else {
            return $this->redirectToRoute('technicien');
        }
    }
    #[Route('/operateur/login', name: 'operateur_login')]
    public function o_login(AuthenticationUtils $utils): Response
    {
        if ($this->getUser() === null) {
            $error = $utils->getLastAuthenticationError();
            $email = $utils->getLastUsername();
    
            return $this->render('login.html.twig', [
                'hasError' => $error !== null,
                'email' => $email
            ]);

        }else {
            return $this->redirectToRoute('technicien');
        }
    }

    #[Route('/administrateur/logout', name: 'administrateur_logout')]
    public function a_logout()
    {
       
    }
    #[Route('/technicien/logout', name: 'technicien_logout')]
    public function t_logout()
    {
        
    }
    #[Route('/operateur/logout', name: 'operateur_logout')]
    public function o_logout()
    {
        
    }

    #[Route('/administrateur', name: 'administrateur_home')]
    public function a_home(): Response
    {
        return $this->render('administrateur/home.html.twig', [
            'title' => 'Administrateur - Home',
        ]);
    }
    #[Route('/technicien', name: 'technicien_home')]
    public function t_home(): Response
    {
        return $this->render('technicien/home.html.twig', [
            'title' => 'Technicien - Home',
        ]);
    }
    #[Route('/operateur', name: 'operateur_home')]
    public function o_home(): Response
    {
        return $this->render('operateur/home.html.twig', [
            'title' => 'Operateur - Home',
        ]);
    }
}
