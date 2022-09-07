<?php

namespace App\Controller;

use App\Form\PictureType;
use App\Entity\Technicien;
use App\Form\PasswordType;
use App\Form\TechnicienType;
use App\Repository\TechnicienRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TechnicienController extends AbstractController
{
    #[Route('/technicien/techniciens', name: 'technicien_technicien_list')]
    public function list(TechnicienRepository $repo): Response
    {
        $techniciens = $repo->findAll();
        return $this->render('technicien/list.html.twig', [
            'title' => 'Technicien - List',
            'techniciens' => $techniciens
        ]);
    }

    #[Route('/administrateur/techniciens', name: 'administrateur_technicien_list')]
    public function a_list(TechnicienRepository $repo): Response
    {
        $techniciens = $repo->findAll();
        return $this->render('technicien/list.html.twig', [
            'title' => 'Technicien - List',
            'techniciens' => $techniciens
        ]);
    }

    #[Route('/operateur/techniciens', name: 'operateur_technicien_list')]
    public function o_list(TechnicienRepository $repo): Response
    {
        $techniciens = $repo->findAll();
        return $this->render('technicien/list.html.twig', [
            'title' => 'Technicien - List',
            'techniciens' => $techniciens
        ]);
    }

    #[Route('/technicien/techniciens/{id}', name: 'technicien_technicien_show')]
    public function t_show(Technicien $technicien): Response
    {
        
        return $this->render('technicien/show.html.twig', [
            'title' => 'Technicien - Show',
            'technicien' => $technicien
        ]);
    }

    #[Route('/administrateur/techniciens/{id}', name: 'administrateur_technicien_show')]
    public function a_show(Technicien $technicien): Response
    {
        
        return $this->render('technicien/show.html.twig', [
            'title' => 'Technicien - Show',
            'technicien' => $technicien
        ]);
    }

    #[Route('/operateur/techniciens/{id}', name: 'operateur_technicien_show')]
    public function o_show(Technicien $technicien): Response
    {
        return $this->render('technicien/show.html.twig', [
            'title' => 'Technicien - Show',
            'technicien' => $technicien
        ]);
    }

    #[Route('/administrateur/techniciens/add', name: 'administrateur_technicien_add')]
    public function add(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        $tech = new Technicien();
        $form = $this->createForm(TechType::class, $tech);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $tech
                ->setHash($encoder->hashPassword($tech, "password"))
                ->setRoles(['ROLE_TECHNICIEN'])
                ->setPicture('avatar.png');
                
            $manager->persist($tech);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouveau technicien <strong>'" . $tech->getFirstname() . ", " . $tech->getLastname() . "'</strong> est ajouté !!!"
            );

            return $this->redirectToRoute('administrateur_technicien_show', [
                'id' => $tech->getId()
            ]);
        }

        return $this->render('technicien/add.html.twig', [
            'title' => 'Technicien - Add',
            'technicien' => $tech,
            'form' => $form->createView()
        ]);
    }

    #[Route('/administrateur/techniciens/{id}/edit', name: 'administrateur_technicien_edit')]
    public function a_edit(Technicien $tech, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(TechnicienType::class, $tech);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                
            $manager->persist($tech);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations du technicien <strong>'" . $tech->getFirstname() . ", " . $tech->getLastname() . "'</strong> ont été modifiées !!!"
            );

            return $this->redirectToRoute('administrateur_technicien_show', [
                'id' => $tech->getId()
            ]);
        }

        return $this->render('technicien/edit.html.twig', [
            'title' => 'Technicien - Edit',
            'tech' => $tech,
            'form' => $form->createView()
        ]);
    }

    #[Route('/technicien/techniciens/{id}/edit', name: 'technicien_technicien_edit')]
    public function t_edit(Technicien $tech, Request $request, EntityManagerInterface $manager): Response
    {
        if ($tech->getId() === $this->getUser()->getId()) {
            $form = $this->createForm(TechnicienType::class, $tech);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                    
                $manager->persist($tech);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Les informations du technicien <strong>'" . $tech->getFirstname() . ", " . $tech->getLastname() . "'</strong> ont été modifiées !!!"
                );

                return $this->redirectToRoute('technicien_technicien_show', [
                    'id' => $tech->getId()
                ]);
            }

            return $this->render('technicien/edit.html.twig', [
                'title' => 'Technicien - Edit',
                'tech' => $tech,
                'form' => $form->createView()
            ]);
        }else {
            return $this->redirectToRoute('technicien_technicien_list');
        }
        
    }

    #[Route('/technicien/techniciens/{id}/picture', name: 'technicien_technicien_picture')]
    public function picture(Technicien $tech, Request $request, EntityManagerInterface $manager): Response
    {
        if ($tech->getId() === $this->getUser()->getId()) {
            $form = $this->createForm(PictureType::class, null);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                
                $picture =  $form->get('picture')->getData();

                if ($picture) {
                    $filename = "tech" . $tech->getId() . "." . $picture->guessExtension();
                    try {
                        $picture->move(
                            $this->getParameter('pictures_directory'),
                            $filename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $tech->setAvatar($filename);
                }

                $manager->persist($tech);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "L'avatar du technicien <strong>'" . $tech->getFirstname() . ", " . $tech->getLastname() . "'</strong> a été modifiée !!!"
                );

                return $this->redirectToRoute('technicien_technicien_show', [
                    'id' => $tech->getId()
                ]);
            }

            return $this->render('technicien/picture.html.twig', [
                'title' => 'Technicien - Picture',
                'technicien' => $tech,
                'form' => $form->createView()
            ]);
        }else {
            return $this->redirectToRoute('technicien_technicien_list');
        }
        
    }

    #[Route('/technicien/techniciens/{id}/password', name: 'technicien_technicien_password')]
    public function password(Technicien $operateur, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        if ($operateur->getId() === $this->getUser()->getId()) {
            $form = $this->createForm(PasswordType::class, $admin);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                $password =  $encoder->hashPassword($operateur, $form->get('password'));
                
                if ($password == $operateur->getPassword()) {
                    $newPassword =  $form->get('newPassword');
                    $manager->persist($operateur);
                    $manager->flush();
                    $this->addFlash(
                        'success',
                        "Votre mot de passe a été modifiée !!!"
                    );
                }else{
                    $this->addFlash(
                        'danger',
                        "L'ancien mot de passe ne correspond pas !!!"
                    );
                }
                return $this->redirectToRoute('technicien_technicien_show', [
                    'id' => $operateur->getId()
                ]);
            }

            return $this->render('site/operateur/password.html.twig', [
                'title' => 'Operateur - Password',
                'form' => $form->createView(),
                'technicien' => $operateur
            ]);
        }else {
            return $this->redirectToRoute('technicien_technicien_list');
        }
        
    }


}
