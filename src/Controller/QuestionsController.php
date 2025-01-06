<?php

namespace App\Controller;

use DateTime;
use App\Entity\Questions;
use App\Form\QuestionsType;
use App\Repository\QuestionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/questions')]
class QuestionsController extends AbstractController
{
    #[Route('/', name: 'app_questions_index', methods: ['GET'])]
    public function index(QuestionsRepository $questionsRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN_LOCAL')) {
            $questions=$questionsRepository->findBy([],['createdAt' => 'DESC']);
        } else {
            $questions=$questionsRepository->findBy(['isVisible' => True],['createdAt' => 'DESC']);
        }
        
        return $this->render('questions/index.html.twig', [
            'questions' => $questions,
        ]);
    }

    #[Route('/new', name: 'app_questions_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADHERENT')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Questions();
        $form = $this->createForm(QuestionsType::class, $question,['priority' => 1]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $question->setCreatedAt(new \DateTimeImmutable());
            $question->setDemandeur($this->getUser());
            $question->setIsVisible(False);
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('app_questions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('questions/new.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_questions_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADHERENT')]
    public function show(Questions $question): Response
    {
        return $this->render('questions/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_questions_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function edit(Request $request, Questions $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionsType::class, $question,['priority' => 1]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $question->setModifiedAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_questions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('questions/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_questions_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function delete(Request $request, Questions $question, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_questions_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/answer', name: 'app_questions_answer', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function answer(Request $request, Questions $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionsType::class, $question,['priority' => 0]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setAnsweredAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_questions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('questions/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/{visible}', name: 'app_questions_visible', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function visible($visible, Questions $question, EntityManagerInterface $entityManager): Response
    {
        //Test de la visibilité pour savoir si lock ou unlock
        if ($visible === 'lock') {
            $question->setIsVisible(true);
        } else {
            $question->setIsVisible(false);
        }
            $entityManager->flush();

        return $this->redirectToRoute('app_questions_index', [], Response::HTTP_SEE_OTHER);

    }
}
