<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Entity\Operateur;
use App\Form\PictureType;
use App\Form\PasswordType;
use App\Form\OperateurType;
use App\Repository\SiteRepository;
use App\Repository\OperateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SiteController extends AbstractController
{
    #[Route('/administrateur/sites', name: 'administrateur_site_list')]
    public function a_index(SiteRepository $repo): Response
    {

        $sites = $repo->findall();

        return $this->render('site/list.html.twig', [
            'title' => 'Site - List',
            'sites' => $sites,
            'role_title' => 'administrateur'
        ]);
    }

    #[Route('/technicien/sites', name: 'technicien_site_list')]
    public function t_index(SiteRepository $repo): Response
    {

        $sites = $repo->findall();

        return $this->render('site/list.html.twig', [
            'title' => 'Site - List',
            'sites' => $sites,
            'role_title' => 'technicien'
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
            'form' => $form->createView(),
            'role_title' => 'administrateur'

        ]);
    }
    #[Route('/administrateur/sites/{id}', name: 'administrateur_site_show')]
    public function a_show($id, SiteRepository $repo): Response
    {   
        $site = $repo->find($id);
        return $this->render('site/show.html.twig', [
            'title' => 'Site - Show',
            'site' => $site,
            'role_title' => 'administrateur'
        ]);
    }

    #[Route('/technicien/sites/{id}', name: 'technicien_site_show')]
    public function t_show($id, SiteRepository $repo): Response
    {
        $site = $repo->find($id);
        return $this->render('site/show.html.twig', [
            'title' => 'Site - Show',
            'site' => $site,
            'role_title' => 'technicien'
        ]);
    }

    #[Route('/operateur/sites/{id}', name: 'operateur_site_show')]
    public function o_show($id, SiteRepository $repo): Response
    {
        $site = $repo->find($id);
        return $this->render('site/show.html.twig', [
            'title' => 'Site - Show',
            'site' => $site,
            'role_title' => 'operateur'
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
            'form' => $form->createView(),
            
            'role_title' => 'administrateur'
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
            'form' => $form->createView(),
            
            'role_title' => 'administrateur'
        ]);
    }

}
