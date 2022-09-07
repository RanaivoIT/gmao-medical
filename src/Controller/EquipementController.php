<?php

namespace App\Controller;

use DateTime;
use App\Entity\Equipement;
use App\Form\EquipementType;
use App\Repository\EquipementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EquipementController extends AbstractController
{
    #[Route('/administrateur/equipements', name: 'administrateur_equipement_list')]
    public function a_list(EquipementRepository $repo): Response
    {
        $equipements = $repo->findAll();
        return $this->render('list/index.html.twig', [
            'title' => 'Equipement - List',
            'equipements' => $equipements
        ]);
    }
    #[Route('/technicien/equipements', name: 'technicien_equipement_list')]
    public function t_list(EquipementRepository $repo): Response
    {
        $equipements = $repo->findAll();
        return $this->render('list/index.html.twig', [
            'title' => 'Equipement - List',
            'equipements' => $equipements
        ]);
    }
    #[Route('/operateur/equipements', name: 'operateur_equipement_list')]
    public function o_list(EquipementRepository $repo): Response
    {
        $equipements = $repo->findBySite($this->getUser()->getSite());
        return $this->render('list/index.html.twig', [
            'title' => 'Equipement - List',
            'equipements' => $equipements
        ]);
    }

    #[Route('/administrateur/equipements/add', name: 'administrateur_equipement_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $equipement = new Equipement();
        $equipement->setUsedAt(new DateTime());
        $equipement->setService('Direction');
        $form = $this->createForm(EquipementType::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $equipement->setCreatedAt(new DateTime());

            $manager->persist($equipement);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouveau equipement <strong>'" . $equipement->getEquipement()->getName() . ", " . $equipement->getSite()->getName() . "'</strong> est ajouté !!!"
            );

            return $this->redirectToRoute('admin_equipement_show', [
                'id' => $equipement->getId()
            ]);
        }

        return $this->render('equipement/add.html.twig', [
            'title' => 'Equipement - Add',
            'equipement' => $equipement,
            'form' => $form->createView()
        ]);
    }

    #[Route('/administrateur/equipements/{id}/edit', name: 'administrateur_equipement_edit')]
    public function edit(Equipement $equipement, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(EquipementType::class, $equipement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($equipement);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations de l'equipement <strong>'" . $equipement->getEquipement()->getName() . ", " . $equipement->getSite()->getName() . "'</strong> ont été modifiés !!!"
            );

            return $this->redirectToRoute('administrateur_equipement_show', [
                'id' => $equipement->getId()
            ]);
        }

        return $this->render('equipement/edit.html.twig', [
            'title' => 'Equipement - Edit',
            'equipement' => $equipement,
            'form' => $form->createView()
        ]);
    }

    #[Route('/administrateur/equipements/{id}', name: 'administrateur_equipement_show')]
    public function a_show(Equipement $equipement): Response
    {
        return $this->render('equipement/show.html.twig', [
            'title' => 'Equipement - Show',
            'equipement' => $equipement
        ]);
    }

    #[Route('/technicien/equipements/{id}', name: 'technicien_equipement_show')]
    public function t_show(Equipement $equipement): Response
    {
        return $this->render('equipement/show.html.twig', [
            'title' => 'Equipement - Show',
            'equipement' => $equipement
        ]);
    }

    #[Route('/operateur/equipements/{id}', name: 'operateur_equipement_show')]
    public function o_show(Equipement $equipement): Response
    {
        return $this->render('equipement/show.html.twig', [
            'title' => 'Equipement - Show',
            'equipement' => $equipement
        ]);
    }
}
