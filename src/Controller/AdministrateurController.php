<?php

namespace App\Controller;

use App\Form\PictureType;
use App\Form\PasswordType;
use App\Entity\Administrateur;
use App\Form\AdministrateurType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AdministrateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdministrateurController extends AbstractController
{
    #[Route('/administrateur/administrateurs', name: 'administrateur_administrateurs')]
    public function list(AdministrateurRepository $repo): Response
    {
        $administrateurs = $repo->findAll();
        return $this->render('administrateur/administrateur/list.html.twig', [
            'title' => 'Administrateurs - List',
            'administrateurs' => $administrateurs
        ]);
    }
    #[Route('/administrateur/administrateurs/{id}', name: 'administrateur_administrateurs_show')]
    public function show(Administrateur $administrateur): Response
    {
        return $this->render('administrateur/administrateur/show.html.twig', [
            'title' => 'Administrateurs - Show',
            'administrateur' => $administrateur
        ]);
    }
    #[Route('/administrateur/administrateurs/add', name: 'administrateur_administrateurs_add')]
    public function add(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        $admin = new Administrateur();
        $form = $this->createForm(AdministrateurType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $admin
                ->setPassword($encoder->hashPassword($admin, "password"))
                ->setRoles(['ROLE_ADMINISTRATEUR'])
                ->setAvatar('avatar.png');
                
            $manager->persist($admin);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouveau administrateur <strong>'" . $admin->getFirstname() . ", " . $admin->getLastname() . "'</strong> est ajouté !!!"
            );

            return $this->redirectToRoute('administrateur_administrateurs_show', [
                'id' => $admin->getId()
            ]);
        }
        return $this->render('administrateur/administrateur/add.html.twig', [
            'title' => 'Administrateurs - Add',
            'form' => $form->createView(),
            'administrateur' => $admin
        ]);
    }
    #[Route('/administrateur/administrateurs/{id}/edit', name: 'administrateur_administrateurs_edit')]
    public function edit(Administrateur $admin, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AdministrateurType::class, $admin);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
                
            $manager->persist($admin);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations de l'administrateur <strong>'" . $admin->getFirstname() . ", " . $admin->getLastname() . "'</strong> ont été modifiés !!!"
            );

            return $this->redirectToRoute('administrateur_administrateurs_show', [
                'id' => $admin->getId()
            ]);
        }

        return $this->render('administrateur/administrateur/edit.html.twig', [
            'title' => 'Administrateurs - Edit',
            'form' => $form->createView(),
            'administrateur' => $admin
        ]);
    }
    #[Route('/administrateur/administrateurs/{id}/password', name: 'administrateur_administrateurs_password')]
    public function password(Administrateur $admin, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        $form = $this->createForm(PasswordType::class, $admin);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
                
            $manager->persist($admin);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre mot de passe a  été modifiée !!!"
            );

            return $this->redirectToRoute('administrateur_administrateurs_show', [
                'id' => $admin->getId()
            ]);
        }

        return $this->render('administrateur/administrateur/password.html.twig', [
            'title' => 'Administrateurs - Password',
            'form' => $form->createView(),
            'administrateur' => $admin
        ]);
    }
    #[Route('/administrateur/administrateurs/{id}/picture', name: 'administrateur_administrateurs_picture')]
    public function picture(): Response
    {
        $form = $this->createForm(PictureType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $picture =  $form->get('picture')->getData();

            if ($picture) {
                $filename = "admin" . $admin->getId() . "." . $picture->guessExtension();
                try {
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $admin->setAvatar($filename);
            }

            $manager->persist($admin);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre avatar a été modifiée !!!"
            );

            return $this->redirectToRoute('administrateur_administrateurs_show', [
                'id' => $admin->getId()
            ]);
        }
        return $this->render('administrateur/administrateur/picture.html.twig', [
            'title' => 'Administrateurs - Picture',
            'form' => $form->createView(),
            'administrateur' => $admin
        ]);
    }

}
