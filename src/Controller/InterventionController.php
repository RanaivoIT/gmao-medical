<?php

namespace App\Controller;

use DateTime;
use App\Entity\Intervention;
use App\Form\InterventionType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InterventionController extends AbstractController
{
    #[Route('/administrateur/interventions', name: 'administrateur_intervention_list')]
    public function a_list(InterventionRepository $repo): Response
    {
        $interventions = $repo->findall();

        return $this->render('intervention/list.html.twig', [
            'title' => 'Intervention - List',
            'interventions' => $interventions
        ]);
    }

    #[Route('/technicien/interventions', name: 'technicien_intervention_list')]
    public function t_list(InterventionRepository $repo): Response
    {
        $interventions = $repo->findall();

        return $this->render('intervention/list.html.twig', [
            'title' => 'Intervention - List',
            'interventions' => $interventions
        ]);
    }

    #[Route('/technicien/interventions', name: 'technicien_intervention_list')]
    public function o_list(InterventionRepository $repo): Response
    {
        $interventions = $repo->findall();

        return $this->render('intervention/list.html.twig', [
            'title' => 'Intervention - List',
            'interventions' => $interventions
        ]);
    }

    #[Route('/administrateur/interventions/add', name: 'administrateur_intervention_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $intervention = new Intervention();
        $intervention->setcreatedAt(new DateTime());
        $intervention->setDemande($demande);
        $form = $this->createForm(InterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $intervention->setCreatedAt(new DateTime());

            $manager->persist($intervention);
            $manager->flush();

            $this->addFlash(
                'success',
                "La nouvelle intervention <strong>'" . $intervention->getEquipement()->getEquipement()->getName() ."'</strong> est ajouté !!!"
            );

            return $this->redirectToRoute('administrateur_intervention_show', [
                'id' => $intervention->getId()
            ]);
        }

        return $this->render('intervention/add.html.twig', [
            'title' => 'Intervention - Add',
            'intervention' => $intervention,
            'form' => $form->createView()
        ]);
    }


    #[Route('/administrateur/interventions/{id}/edit', name: 'administrateur_intervention_edit')]
    public function edit(Intervention $intervention, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(InterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($intervention);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations d' intervention <strong>'" . $intervention->getEquipement()->getName() . "'</strong> ont été modifiés !!!"
            );

            return $this->redirectToRoute('administrateur_intervention_show', [
                'id' => $intervention->getId()   
            ]);
        }

        return $this->render('intervention/edit.html.twig', [
            'title' => 'Admin - Intervention',
            'intervention' => $intervention,
            'form' => $form->createView()
        ]);
    }

    #[Route('/technicien/interventions/{id}/edit', name: 'technicien_intervention_edit')]
    public function t_edit(Intervention $intervention, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(InterventionType::class, $intervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($intervention);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations d' intervention <strong>'" . $intervention->getEquipement()->getName() . "'</strong> ont été modifiés !!!"
            );

            return $this->redirectToRoute('administrateur_intervention_show', [
                'id' => $intervention->getId()   
            ]);
        }

        return $this->render('intervention/edit.html.twig', [
            'title' => 'Admin - Intervention',
            'intervention' => $intervention,
            'form' => $form->createView()
        ]);
    }

    #[Route('/administrateur/interventions/{id}', name: 'administrateur_intervention_show')]
    public function a_show(Intervention $intervention): Response
    {
        return $this->render('intervention/show.html.twig', [
            'title' => 'Intervention -Show',
            'intervention' => $intervention
        ]);
    }

    #[Route('/technicien/interventions/{id}', name: 'technicien_intervention_show')]
    public function t_show(Intervention $intervention): Response
    {
        return $this->render('intervention/show.html.twig', [
            'title' => 'Intervention -Show',
            'intervention' => $intervention
        ]);
    }

    #[Route('/operateur/interventions/{id}', name: 'operateur_intervention_show')]
    public function o_show(Intervention $intervention): Response
    {
        return $this->render('intervention/show.html.twig', [
            'title' => 'Intervention -Show',
            'intervention' => $intervention
        ]);
    }

}
