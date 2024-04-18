<?php

namespace App\Controller;

use App\Entity\Marques;
use App\Form\MarquesType;
use App\Repository\MarquesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/marques')]
class MarquesController extends AbstractController
{
    #[Route('/', name: 'app_marques_index', methods: ['GET'])]
    public function index(MarquesRepository $marquesRepository): Response
    {
        return $this->render('marques/index.html.twig', [
            'marques' => $marquesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_marques_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $marque = new Marques();
        $form = $this->createForm(MarquesType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($marque);
            $entityManager->flush();

            return $this->redirectToRoute('app_marques_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('marques/new.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_marques_show', methods: ['GET'])]
    public function show(Marques $marque): Response
    {
        return $this->render('marques/show.html.twig', [
            'marque' => $marque,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_marques_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Marques $marque, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MarquesType::class, $marque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_marques_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('marques/edit.html.twig', [
            'marque' => $marque,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_marques_delete', methods: ['POST'])]
    public function delete(Request $request, Marques $marque, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marque->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($marque);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_marques_index', [], Response::HTTP_SEE_OTHER);
    }
}
