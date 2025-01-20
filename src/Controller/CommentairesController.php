<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use App\Repository\SujetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commentaires')]
#[IsGranted('ROLE_ADHERENT')]
class CommentairesController extends AbstractController
{
    #[Route('/', name: 'app_commentaires_index', methods: ['GET'])]
    public function index(CommentairesRepository $commentairesRepository): Response
    {
        return $this->render('commentaires/index.html.twig', [
            'commentaires' => $commentairesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commentaires_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SujetsRepository $sujet): Response
    {
        $commentaire = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /** @var User $user*/
             $user = $this->getUser();
             $DateJour = new \DateTimeImmutable();
             $jour=$DateJour->modify('today');
             $commentaire->setCreatedAt($jour);
             $commentaire->setCreatedBy($user);
             $commentaire->setSujets($sujet->findOneById($request->get('id')));

            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_sujets_show', ['id' => $request->get('id')], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaires/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaires_show', methods: ['GET'])]
    public function show(Commentaires $commentaire): Response
    {
        return $this->render('commentaires/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaires_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function edit(Request $request, Commentaires $commentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaires/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commentaires_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function delete(Request $request, Commentaires $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaires_index', [], Response::HTTP_SEE_OTHER);
    }
}
