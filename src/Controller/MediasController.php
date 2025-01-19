<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Medias;
use App\Form\MediasType;
use App\Repository\MediasRepository;
use App\Repository\SiteRepository;
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
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function new(Request $request, EntityManagerInterface $entityManager, SiteRepository $siteRepository): Response
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
            if ($form->get('couverture')->getData()) {
                /** @var UploadedFile $imageCover */
                $imageCover=$form->get('couverture')->getData();
                $imagename=$imageCover->getClientOriginalName();
                $imageCover->move($this->getParameter('kernel.project_dir').'/public/medias', $imagename);
            } else {
                $imagename="image_default.png";
            }
     
            $media->setFichierPath($filename);
            $media->setImage($imagename);
            $media->setCreatedBy($user);
            $media->setIsArchived(false);
            //Suivant périmètre recupérer la donnée qui correspond
            $media->setPerimetre($this->setTbPerimetre($form, $user, $siteRepository));
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
    #[IsGranted('ROLE_ADHERENT')]
    public function show(Medias $media): Response
    {
        return $this->render('medias/show.html.twig', [
            'media' => $media,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_medias_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function edit(Request $request, Medias $media, EntityManagerInterface $entityManager, SiteRepository $siteRepository): Response
    {
        $form = $this->createForm(MediasType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user*/
            $user = $this->getUser();
            //Récupération du type de média pour filtrer plus finement par la suite
            $type=$form->get('type')->getData();
            if ($form->get('fichier')->getData()) {
                /** @var UploadedFile $file */
                $file=$form->get('fichier')->getData();
                $filename=$file->getClientOriginalName();
                $file->move($this->getParameter('kernel.project_dir').'/public/medias', $filename);
                $media->setFichierPath($filename);
            }
            if ($form->get('couverture')->getData()) {
                /** @var UploadedFile $imageCover */
                $imageCover=$form->get('couverture')->getData();
                $imagename=$imageCover->getClientOriginalName();
                $imageCover->move($this->getParameter('kernel.project_dir').'/public/medias', $imagename);
                $media->setImage($imagename);
            } elseif(!$media->getImage() AND !$form->get('couverture')->getData()) {
                $imagename="image_default.png";
                $media->setImage($imagename);
            }
            
            $DateJour = new \DateTimeImmutable();
            $jour=$DateJour->modify('today');
            //Gestion du qui pourra voir le média
            $media->setPerimetre($this->setTbPerimetre($form, $user, $siteRepository));
            $media->setModifedAt($jour);
            $media->setModifedBy($user);
            
            $entityManager->persist($media);
            $entityManager->flush();

            return $this->redirectToRoute('app_medias_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('medias/edit.html.twig', [
            'media' => $media,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_medias_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function delete(Request $request, Medias $media, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$media->getId(), $request->request->get('_token'))) {
            $entityManager->remove($media);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_medias_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/download/{id}', name: 'app_medias_download')]
    #[IsGranted('ROLE_USER')]
    public function download(int $id, Medias $media, EntityManagerInterface $entityManager): Response
    {
        $repoFichier = $entityManager->getRepository(Medias::class); 
        $media = $repoFichier->find($id);
        if ($media == null){
            $this->addFlash(
               'danger',
               'Le fichier n\'existe pas'
            ); }
        else{
            return $this->file($_SERVER['DOCUMENT_ROOT'].'medias/'.$media->getFichierPath());
        }
    }
    
    #[Route('/archived/{id}', name: 'app_medias_archived')]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function archived(int $id, Medias $media, EntityManagerInterface $entityManager): Response
    {
        $repoFichier = $entityManager->getRepository(Medias::class); 
        $media = $repoFichier->find($id);
        if ($media == null){
            $this->addFlash(
               'danger',
               'Le fichier n\'existe pas'
            ); }
        else{
            $media->setIsArchived(true);
            $entityManager->persist($media);
            $entityManager->flush();
        }
        $this->addFlash(
            'success',
            'Le média ne sera plus visible, si erreur contacter l\'admin via contact'
         );
        return $this->redirectToRoute('app_medias_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete_file/{id}', name: 'app_medias_delete_file')]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function delete_file(int $id, Medias $media, EntityManagerInterface $entityManager)
    {
        $repoFichier = $entityManager->getRepository(Medias::class); 
        $media = $repoFichier->find($id);
        if ($media == null){
            $this->addFlash(
               'danger',
               'Le fichier n\'existe pas'
            ); }
        else{
            // On récupère le nom du fichier
            $nom = $media->getFichierPath();
            // On supprime le fichier
            unlink($_SERVER['DOCUMENT_ROOT'].'medias/'.$nom);

            // On supprime l'entrée de la base
            $media->setFichierPath();
            $entityManager->persist($media);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le fichier '.$nom . ' a bien été supprimé'
             );
        }
    }

    #[Route('/delete_image/{id}', name: 'app_medias_delete_image')]
    #[IsGranted('ROLE_ADMIN_LOCAL')]
    public function delete_image(int $id, Medias $media, EntityManagerInterface $entityManager)
    {
        $repoFichier = $entityManager->getRepository(Medias::class); 
        $media = $repoFichier->find($id);
        if ($media == null){
            $this->addFlash(
               'danger',
               'Le fichier n\'existe pas'
            ); }
        else{
            // On récupère le nom du fichier
            $nom = $media->getImage();

            // On supprime le fichier
            unlink($this->getParameter('kernel.project_dir').'/public/medias/'.$nom);

            // On supprime l'entrée de la base
            $media->setImage(null);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Le fichier '.$nom . ' a bien été supprimé'
             );

             return $this->redirectToRoute('app_medias_show', ['id' => $media->getId()], Response::HTTP_SEE_OTHER);
        }
    }

    // @ Création du système de comptage des vues/likes/unlikes

    public function setTbPerimetre($form, User $user, SiteRepository $repo): string
    {
        $tbPerimetre=[];
        switch ($form->get('perimetre')->getData()) {
            case 'Site':
                $Site=$repo->findByUserID($user->getId());
                $tbPerimetre=$form->get('perimetre')->getData().'/'.$Site[0]->getname();
                break;
            case 'Division':
                $Division=$user->getDivision();
                $tbPerimetre=$form->get('perimetre')->getData().'/'.$Division->getname();
                break;
            case 'Convention':
                $Site=$repo->findByUserID($user->getId());
                $convention=$Site[0]->getConvention();
                $tbPerimetre=$form->get('perimetre')->getData().'/'.$convention[0]->getName();
                break;
            case 'Groupe':
                $Groupe='DAHER';
                $tbPerimetre=$form->get('perimetre')->getData().'/'.$Groupe;
                break;          
            default:
                break;
        }

        return $tbPerimetre;
    }
}
