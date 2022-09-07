<?php

namespace App\Controller;

use App\Entity\Site;
use App\Entity\Operateur;
use App\Form\PictureType;
use App\Form\PasswordType;
use App\Form\OperateurType;
use App\Repository\SiteRepository;
use App\Repository\OperateurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SiteController extends AbstractController
{
    #[Route('/administrateur/sites', name: 'administrateur_site')]
    public function a_index(SiteRepository $repo): Response
    {

        $sites = $repo->findall();

        return $this->render('site/index.html.twig', [
            'title' => 'Site - List',
            'sites' => $sites
        ]);
    }

    #[Route('/technicien/sites', name: 'technicien_site')]
    public function t_index(SiteRepository $repo): Response
    {

        $sites = $repo->findall();

        return $this->render('site/index.html.twig', [
            'title' => 'Site - List',
            'sites' => $sites
        ]);
    }

    #[Route('/administrateur/sites/{id}', name: 'administrateur_site_show')]
    public function a_show(Site $site): Response
    {
        return $this->render('site/show.html.twig', [
            'title' => 'Site - Show',
            'site' => $site
        ]);
    }

    #[Route('/technicien/sites/{id}', name: 'technicien_site_show')]
    public function t_show(Site $site): Response
    {
        return $this->render('site/show.html.twig', [
            'title' => 'Site - Show',
            'site' => $site
        ]);
    }

    #[Route('/operateur/sites/{id}', name: 'operateur_site_show')]
    public function o_show(Site $site): Response
    {
        return $this->render('site/show.html.twig', [
            'title' => 'Site - Show',
            'site' => $site
        ]);
    }

    #[Route('/administrateur/sites/add', name: 'administrateur_site_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $site = new Site();

        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $site
                ->setPicture('site.jpg');
                
            $manager->persist($site);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouveau site <strong>'" . $site->getName(). "'</strong> est ajouté !!!"
            );

            return $this->redirectToRoute('administrateur_site_show', [
                'id' => $site->getId()
            ]);
        }

        return $this->render('site/add.html.twig', [
            'title' => 'Site - Add',
            'site' => $site,
            'form' => $form->createView()
        ]);
    }

    #[Route('/administrateur/sites/{id}/edit', name: 'administrateur_site_edit')]
    public function edit(Site $site, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($site);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations du site <strong>'" . $site->getName() . "'</strong> ont été modifiés !!!"
            );

            return $this->redirectToRoute('administrateur_site_show', [
                'id' => $site->getId()
            ]);
        }

        return $this->render('site/edit.html.twig', [
            'title' => 'Site - Edit',
            'site' => $site,
            'form' => $form->createView()
        ]);
    }

    #[Route('/administrateur/sites/{id}/change-picture', name: 'administrateur_site_picture')]
    public function change_picture(Site $site, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(PictureType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $picture =  $form->get('picture')->getData();

            if ($picture) {
                $filename = "site" . $site->getId() . "." . $picture->guessExtension();
                try {
                    $picture->move(
                        $this->getParameter('pictures_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $site->setPicture($filename);
            }

            $manager->persist($site);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'image du site <strong>'" . $site->getName() . "'</strong> a été modifiée !!!"
            );

            return $this->redirectToRoute('administrateur_site_show', [
                'id' => $site->getId()
            ]);
        }

        return $this->render('site/picture.html.twig', [
            'title' => 'Site - Picture',
            'site' => $site,
            'form' => $form->createView()
        ]);
    }

    //operateur
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

    #[Route('/operateur/sites/{id}/operateurs', name: 'operateur_operateur_list')]
    public function o_operateur_list(Site $site): Response
    {
        $operateurs = $site->getOperateurs();
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
