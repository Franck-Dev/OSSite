<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Medias;
use App\Form\MediasType;
use App\Repository\MediasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/medias')]
#[IsGranted('ROLE_ADHERENT')]
class MediasController extends AbstractController
{
    #[Route('/', name: 'app_medias_index', methods: ['GET'])]
    public function index(MediasRepository $mediasRepository): Response
    {
        return $this->render('medias/index.html.twig', [
            'medias' => $mediasRepository->findByTypeMedias(10),
        ]);
    }

    #[Route('/new', name: 'app_medias_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $media = new Medias();
        $form = $this->createForm(MediasType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user*/
            $user = $this->getUser();
            //Récupération du type de média pour filtrer plus finement par la suite
            $type=$form->get('type')->getData();
            /** @var UploadedFile $file */
            $file=$form->get('fichier')->getData();
            $filename=$file->getClientOriginalName();
            $file->move($this->getParameter('kernel.project_dir').'/public/medias', $filename);
            /** @var UploadedFile $imageCover */
            $imageCover=$form->get('couverture')->getData();
            $imagename=$imageCover->getClientOriginalName();
            $imageCover->move($this->getParameter('kernel.project_dir').'/public/medias', $imagename);
            
            $media->setFichierPath($filename);
            $media->setImage($imagename);
            $media->setCreatedBy($user);
            $media->setIsArchived(false);

            $DateJour = new \DateTimeImmutable();
            $jour=$DateJour->modify('today');
            $media->setCreatedAt($jour);
            $entityManager->persist($media);
            $entityManager->flush();

            $this->addFlash('success','Le média '. $type->getLibelle(). ', '. $filename. 'a bien été créé');

            return $this->redirectToRoute('app_medias_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('medias/new.html.twig', [
            'media' => $media,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_medias_show', methods: ['GET'])]
    public function show(Medias $media): Response
    {
        return $this->render('medias/show.html.twig', [
            'media' => $media,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_medias_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Medias $media, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MediasType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_medias_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('medias/edit.html.twig', [
            'media' => $media,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_medias_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Medias $media, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$media->getId(), $request->request->get('_token'))) {
            $entityManager->remove($media);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_medias_index', [], Response::HTTP_SEE_OTHER);
    }
}
