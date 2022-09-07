<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperateurController extends AbstractController
{
    #[Route('/administrateur/operateurs', name: 'administrateur_operateur_list')]
    public function a_operateur_list(OperateurRepository $repo): Response
    {
        $operateurs = $repo->findAll();
        return $this->render('site/operateur/index.html.twig', [
            'title' => 'Operateur - List',
            'operateurs' => $operateurs
        ]);
    }

    #[Route('/technicien/operateurs', name: 'technicien_operateur_list')]
    public function t_operateur_list(OperateurRepository $repo): Response
    {
        $operateurs = $repo->findAll();
        return $this->render('site/operateur/index.html.twig', [
            'title' => 'Operateur - List',
            'operateurs' => $operateurs
        ]);
    }

    #[Route('/operateur/operateurs', name: 'operateur_operateur_list')]
    public function o_operateur_list(OperateurRepository $repo): Response
    {
        $operateurs = $repo->findBySite($this->getUser()->getSite());
        return $this->render('site/operateur/index.html.twig', [
            'title' => 'Operateur - List',
            'operateurs' => $operateurs
        ]);
    }

    #[Route('/administrateur/operateurs/{id}', name: 'administrateur_operateur_show')]
    public function a_operateur_show(Operateur $operateur): Response
    {
        return $this->render('site/operateur/show.html.twig', [
            'title' => 'Operateur - Show',
            'operateur' => $operateur
        ]);
    }
    #[Route('/technicien/operateurs/{id}', name: 'technicien_operateur_show')]
    public function t_operateur_show(Operateur $operateur): Response
    {
        return $this->render('site/operateur/show.html.twig', [
            'title' => 'Operateur - Show',
            'operateur' => $operateur
        ]);
    }
    #[Route('/operateur/operateurs/{id}', name: 'operateur_operateur_show')]
    public function o_operateur_show(Operateur $operateur): Response
    {
        return $this->render('site/operateur/show.html.twig', [
            'title' => 'Operateur - Show',
            'operateur' => $operateur
        ]);
    }

    #[Route('/administrateur/operateurs/{id}/edit', name: 'administrateur_operateur_edit')]
    public function a_operateur_edit(Operateur $operateur, Request $request, EntityManagerInterface $manager): Response
    {
        
        $form = $this->createForm(OperateurType::class, $operateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                
            $manager->persist($operateur);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations de l'utilisateur <strong>'" . $operateur->getFirstname() . ", " . $operateur->getLastname() . "'</strong> ont été modifiées !!!"
            );

            return $this->redirectToRoute('administrateur_operateur_show', [
                'id' => $operateur->getId()
            ]);
        }

        return $this->render('sie/operateur/edit.html.twig', [
            'title' => 'Operateur - Edit',
            'operateur' => $operateur,
            'form' => $form->createView()
        ]);
        
        
    }

    #[Route('/operateur/operateurs/{id}/edit', name: 'operateur_operateur_edit')]
    public function o_operateur_edit(Operateur $operateur, Request $request, EntityManagerInterface $manager): Response
    {
        if ($operateur->getId() === $this->getUser()->getId()) {
            $form = $this->createForm(OperateurType::class, $operateur);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                    
                $manager->persist($operateur);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Les informations de l'utilisateur <strong>'" . $operateur->getFirstname() . ", " . $operateur->getLastname() . "'</strong> ont été modifiées !!!"
                );

                return $this->redirectToRoute('operateur_operateur_show', [
                    'id' => $operateur->getId()
                ]);
            }

            return $this->render('sie/operateur/edit.html.twig', [
                'title' => 'Operateur - Edit',
                'operateur' => $operateur,
                'form' => $form->createView()
            ]);
        }else {
            return $this->redirectToRoute('operateur_operateur_show', [
                'id' => $this->getUser()->getId()
            ]);
        }
        
    }

    #[Route('/operateur/operateurs/{id}/picture', name: 'operateur_operateur_picture')]
    public function picture(Operateur $operateur, Request $request, EntityManagerInterface $manager): Response
    {
        if ($operateur->getId() === $this->getUser()->getId()) {
            $form = $this->createForm(PictureType::class, null);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                
                $picture =  $form->get('picture')->getData();

                if ($picture) {
                    $filename = "operateur" . $operateur->getId() . "." . $picture->guessExtension();
                    try {
                        $picture->move(
                            $this->getParameter('pictures_directory'),
                            $filename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $operateur->setAvatar($filename);
                }

                $manager->persist($operateur);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre picture <strong>'" . $operateur->getFirstname() . ", " . $operateur->getLastname() . "'</strong> a été modifiée !!!"
                );

                return $this->redirectToRoute('operateur_operateur_show', [
                    'id' => $operateur->getId()
                ]);
            }

            return $this->render('site/operateur/avatar.html.twig', [
                'title' => 'Operateur - Picture',
                'operateur' => $operateur,
                'form' => $form->createView()
            ]);
        }else {
            return $this->redirectToRoute('operateur_operateur_list');
        }
        
    }

    #[Route('/operateur/operateurs/{id}/password', name: 'operateur_operateur_password')]
    public function password(Operateur $operateur, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
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
                return $this->redirectToRoute('operateur_operateur_show', [
                    'id' => $operateur->getId()
                ]);
            }

            return $this->render('site/operateur/password.html.twig', [
                'title' => 'Operateur - Password',
                'form' => $form->createView(),
                'operateur' => $operateur
            ]);
        }else {
            return $this->redirectToRoute('operateur_operateur_list');
        }
        
    }

    #[Route('/administrateur/operateurs/add', name: 'administrateur_operateur_add')]
    public function operateur_add(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $encoder): Response
    {
        $operateur = new Operateur();
        $form = $this->createForm(OperateurType::class, $operateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user
                ->setHash($encoder->hashPassword($operateur, "password"))
                ->setRoles(['ROLE_OPERATEUR'])
                ->setPicture('avatar.png');
                
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouveau operateur <strong>'" . $user->getFirstname() . ", " . $user->getLastname() . "'</strong> est ajouté !!!"
            );

            return $this->redirectToRoute('aadministrateur_operateur_show', [
                'id' => $operateur->getId()
            ]);
        }

        return $this->render('site/operateur/add.html.twig', [
            'title' => 'Operateur - Add',
            'operateur' => $operateur,
            'form' => $form->createView()
        ]);
    }

}
