<?php

namespace App\Controller;

use App\Entity\Piece;
use App\Entity\Organe;
use App\Form\PieceType;
use App\Entity\Document;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\ColectionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ColectionController extends AbstractController
{
    #[Route('/administrateur/collections', name: 'administrateur_colection_list')]
    public function a_list(ColectionRepository $repo): Response
    {
        $colections = $repo->findAll();
        return $this->render('colection/index.html.twig', [
            'title' => 'Collection - List',
            'colections' => $colections,
        ]);
    }
    #[Route('/technicien/collections', name: 'technicien_colection_list')]
    public function t_list(ColectionRepository $repo): Response
    {
        $colections = $repo->findAll();
        return $this->render('colection/index.html.twig', [
            'title' => 'Collection - List',
            'colections' => $colections,
        ]);
    }

    #[Route('/administrateur/colections/add', name: 'administrateur_colection_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $colection = new Colection();

        $form = $this->createForm(ColectionType::class, $colection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $colection
                ->setPicture('cogs.jpg');
                
            $manager->persist($colection);
            $manager->flush();

            $this->addFlash(
                'success',
                "La nouvelle collection <strong>'" . $colection->getName(). "'</strong> est ajoutée !!!"
            );

            return $this->redirectToRoute('administrateur_colection_show', [
                'id' => $colection->getId()
            ]);
        }

        return $this->render('colection/add.html.twig', [
            'title' => 'Collection - Add',
            'colection' => $colection,
            'form' => $form->createView()
        ]);
    }

    #[Route('/administrateur/colections/{id}', name: 'administrateur_colection_show')]
    public function a_show(Colection $colection): Response
    {
        return $this->render('colection/show.html.twig', [
            'title' => 'Collection - Show',
            'colection' => $colection
        ]);
    }

    #[Route('/technicien/colections/{id}', name: 'technicien_colection_show')]
    public function t_show(Colection $colection): Response
    {
        return $this->render('colection/show.html.twig', [
            'title' => 'Collection - Show',
            'colection' => $colection
        ]);
    }

    #[Route('/administrateur/colections/{id}/edit', name: 'administrateur_colection_edit')]
    public function edit(Colection $colection, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ColectionType::class, $colection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($colection);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les informations de la collection <strong>'" . $colection->getName() . "'</strong> ont été modifiés !!!"
            );

            return $this->redirectToRoute('administrateur_colection_show', [
                'id' => $colection->getId()
            ]);
        }

        return $this->render('colection/edit.html.twig', [
            'title' => 'Collection - Edit',
            'colection' => $colection,
            'form' => $form->createView()
        ]);
    }

    //organe

    #[Route('/administrateur/collections/{id}/organes', name: 'administrateur_organe_list')]
    public function a_organe_list(Colection $colection): Response
    {
        $organes = $colection->getOrganes();
        return $this->render('colection/organe/index.html.twig', [
            'title' => 'Organe - List',
            'organes' => $organes,
        ]);
    }
    #[Route('/technicien/collections/{id}/organes', name: 'technicien_organe_list')]
    public function t_organe_list(Colection $colection): Response
    {
        $organes = $colection->getOrganes();
        return $this->render('colection/organe/index.html.twig', [
            'title' => 'Organe - List',
            'organes' => $organes,
        ]);
    }

    #[Route('/administrateur/organes/{id}', name: 'administrateur_organe_show')]
    public function a_organe_show(Organe $organe): Response
    {
        return $this->render('colection/organe/show.html.twig', [
            'title' => 'Organe - Show',
            'organe' => $organe
        ]);
    }
    #[Route('/technicien/organes/{id}', name: 'technicien_organe_show')]
    public function t_organe_show(Organe $organe): Response
    {
        return $this->render('colection/organe/show.html.twig', [
            'title' => 'Organe - Show',
            'organe' => $organe
        ]);
    }

    #[Route('/administrateur/collections/{id}/organes/add', name: 'administrateur_organe_add')]
    public function a_organe_add(Colection $colection): Response
    {
        $organe = new Organe();
        $organe->setColection($colection);

        $form = $this->createForm(OrganeType::class, $organe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($organe);
            $manager->flush();

            $this->addFlash(
                'success',
                "La nouvelle organe <strong>'" . $organe->getName(). "'</strong> est ajoutée !!!"
            );

            return $this->redirectToRoute('administrateur_organe_show', [
                'id' => $organe->getId()
            ]);
        }

        return $this->render('colection/organe/add.html.twig', [
            'title' => 'Organe - Add',
            'organe' => $organe,
            'form' => $form->createView()
        ]);
    }

    #[Route('/administrateur/organes/{id}/edit', name: 'administrateur_organe_show')]
    public function a_organe_edit(Organe $organe): Response
    {
        $form = $this->createForm(OrganeType::class, $organe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($organe);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'organe <strong>'" . $organe->getName(). "'</strong> a été modifiée !!!"
            );

            return $this->redirectToRoute('administrateur_organe_show', [
                'id' => $organe->getId()
            ]);
        }

        return $this->render('colection/organe/edit.html.twig', [
            'title' => 'Organe - Edit',
            'organe' => $organe,
            'form' => $form->createView()
        ]);
    }

    //piece
    #[Route('/administrateur/organes/{id}/pieces', name: 'administrateur_piece_list')]
    public function a_piece_list(Piece $piece): Response
    {
        $pieces = $organe->getPieces();
        return $this->render('colection/organe/piece/index.html.twig', [
            'title' => 'Piece - List',
            'pieces' => $pieces,
        ]);
    }
    #[Route('/technicien/organes/{id}/list', name: 'technicien_piece_list')]
    public function t_piece_list(Piece $piece): Response
    {
        $pieces = $organe->getPieces();
        return $this->render('colection/organe/piece/index.html.twig', [
            'title' => 'Piece - List',
            'pieces' => $pieces,
        ]);
    }

    #[Route('/administrateur/pieces/{id}', name: 'administrateur_piece_show')]
    public function a_piece_show(Piece $piece): Response
    {
        return $this->render('colection/organe/piece/show.html.twig', [
            'title' => 'Piece - Show',
            'piece' => $piece
        ]);
    }
    #[Route('/technicien/pieces/{id}', name: 'administrateur_piece_show')]
    public function t_piece_show(Piece $piece): Response
    {
        return $this->render('colection/organe/piece/show.html.twig', [
            'title' => 'Piece - Show',
            'organe' => $organe
        ]);
    }

    #[Route('/administrateur/organes/{id}/add', name: 'administrateur_piece_add')]
    public function a_piece_add(Organe $organe): Response
    {
        $piece = new Piece();
        $piece->setOrgane($organe);

        $form = $this->createForm(PieceType::class, $piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($piece);
            $manager->flush();

            $this->addFlash(
                'success',
                "La nouvelle piece <strong>'" . $piece->getName(). "'</strong> est ajoutée !!!"
            );

            return $this->redirectToRoute('administrateur_piece_show', [
                'id' => $piece->getId()
            ]);
        }

        return $this->render('colection/organe/piece/add.html.twig', [
            'title' => 'Piece - Add',
            'organe' => $organe,
            'form' => $form->createView()
        ]);
    }

    #[Route('/administrateur/pieces/{id}/edit', name: 'administrateur_piece_show')]
    public function a_piece_edit(Piece $piece): Response
    {
        $form = $this->createForm(OrganeType::class, $piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($piece);
            $manager->flush();

            $this->addFlash(
                'success',
                "La piece <strong>'" . $piece->getName(). "'</strong> a été modifiée !!!"
            );

            return $this->redirectToRoute('administrateur_piece_show', [
                'id' => $piece->getId()
            ]);
        }

        return $this->render('colection/organe/piece/edit.html.twig', [
            'title' => 'Piece - Edit',
            'piece' => $piece,
            'form' => $form->createView()
        ]);
    }

    // document

    #[Route('/administrateur/collections/{id}/documents', name: 'administrateur_document_list')]
    public function a_document_list(Colection $colection): Response
    {
        $documents = $colection->getDocuments();
        return $this->render('colection/document/index.html.twig', [
            'title' => 'Document - List',
            'documents' => $documents,
        ]);
    }
    #[Route('/technicien/collections/{id}/documents', name: 'technicien_document_list')]
    public function t_document_list(Colection $colection): Response
    {
        $documents = $colection->getDocuments();
        return $this->render('colection/document/index.html.twig', [
            'title' => 'Document - List',
            'documents' => $documents,
        ]);
    }

    #[Route('/administrateur/documents/{id}', name: 'administrateur_document_show')]
    public function a_document_show(Document $document): Response
    {
        return $this->render('colection/document/show.html.twig', [
            'title' => 'Document - Show',
            'document' => $document
        ]);
    }
    #[Route('/technicien/documents/{id}', name: 'technicien_document_show')]
    public function t_document_show(Document $document): Response
    {
        return $this->render('colection/document/show.html.twig', [
            'title' => 'Document - Show',
            'document' => $document
        ]);
    }

    #[Route('/administrateur/collections/{id}/documents/add', name: 'administrateur_document_add')]
    public function a_document_add(Colection $colection): Response
    {
        $document = new Document();
        $document->setColection($colection);

        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($document);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le nouveau document <strong>'" . $document->getName(). "'</strong> est ajoutée !!!"
            );

            return $this->redirectToRoute('administrateur_document_show', [
                'id' => $document->getId()
            ]);
        }

        return $this->render('colection/document/add.html.twig', [
            'title' => 'Document - Add',
            'document' => $document,
            'form' => $form->createView()
        ]);
    }

    #[Route('/administrateur/documents/{id}/edit', name: 'administrateur_document_show')]
    public function a_document_edit(Document $document): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($document);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le document <strong>'" . $document->getName(). "'</strong> a été modifiée !!!"
            );

            return $this->redirectToRoute('administrateur_document_show', [
                'id' => $document->getId()
            ]);
        }

        return $this->render('colection/document/edit.html.twig', [
            'title' => 'Document - Edit',
            'document' => $document,
            'form' => $form->createView()
        ]);
    }


}
