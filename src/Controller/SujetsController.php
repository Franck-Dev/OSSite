<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Sujets;
use DateTimeImmutable;
use App\Form\SujetsType;
use App\Service\GestionPerimetre;
use App\Repository\SiteRepository;
use App\Repository\SujetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/sujets')]
class SujetsController extends AbstractController
{
    #[Route('/', name: 'app_sujets_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADHERENT')]
    public function index(SujetsRepository $sujetsRepository): Response
    {
        return $this->render('sujets/index.html.twig', [
            'sujets' => $sujetsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sujets_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function new(Request $request, EntityManagerInterface $entityManager,
    SiteRepository $siteRepository, GestionPerimetre $perimetre): Response
    {
        $sujet = new Sujets();
        $form = $this->createForm(SujetsType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user*/
            $user = $this->getUser();
            $DateJour = new \DateTimeImmutable();
            $jour=$DateJour->modify('today');
            $sujet->setCreatedAt($jour);
            $sujet->setPerimetre($perimetre->setTbPerimetre($form, $user, $siteRepository));

            $entityManager->persist($sujet);
            $entityManager->flush();

            return $this->redirectToRoute('app_sujets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sujets/new.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sujets_show', methods: ['GET'])]
    public function show(Sujets $sujet): Response
    {
        return $this->render('sujets/show.html.twig', [
            'sujet' => $sujet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sujets_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function edit(Request $request, Sujets $sujet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SujetsType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sujets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sujets/edit.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sujets_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function delete(Request $request, Sujets $sujet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sujet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sujet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sujets_index', [], Response::HTTP_SEE_OTHER);
    }
}
