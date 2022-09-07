<?php

namespace App\Controller;

use DateTime;
use App\Form\DemandeType;
use App\Repository\DemandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DemandeController extends AbstractController
{
    #[Route('/operateur/demandes', name: 'operateur_demande_list')]
    public function o_list(DemandeRepository $repo): Response
    {

        $demandes = $repo->findBySite($this->getUser()->getSite());

        return $this->render('demande/list.html.twig', [
            'title' => 'Demande - List',
            'demandes' => $demandes
        ]);
    }

    #[Route('/administrateur/demandes', name: 'administrateur_demande_list')]
    public function a_list(DemandeRepository $repo): Response
    {

        $demandes = $repo->findAll();

        return $this->render('demande/list.html.twig', [
            'title' => 'Demande - List',
            'demandes' => $demandes
        ]);
    }

    #[Route('/operateur/equipements/{id}/demandes/add/', name: 'operateur_demande_add')]
    public function add(Equipement $equipement,  Request $request, EntityManagerInterface $manager): Response
    {
        $demande = new Demande();
        $demande->setEquipement($equipement);
        
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $demande->setCreatedAt(new DateTime());

            $manager->persist($demande);
            $manager->flush();

            $this->addFlash(
                'success',
                "La nouvelle demande <strong>'" . $demande->getEquipement()->getName() . "'</strong> est ajoutée !!!"
            );

            return $this->redirectToRoute('operateur_demande_show', [
                'id' => $demande->getId()
            ]);
        }

        return $this->render('demande/add.html.twig', [
            'title' => 'Demande - Add',
            'demande' => $demande,
            'form' => $form->createView()
        ]);
    }

    #[Route('/operateur/demandes/{id}', name: 'operateur_demande_show')]
    public function o_show(Demande $demande): Response
    {
        return $this->render('demande/show.html.twig', [
            'title' => 'Demande - Show',
            'demande' => $demande
        ]);
    }

    #[Route('/administrateur/demandes/{id}', name: 'administrateur_demande_show')]
    public function a_show(Demande $demande): Response
    {
        return $this->render('demande/show.html.twig', [
            'title' => 'Demande - Show',
            'demande' => $demande
        ]);
    }
}
