<?php

namespace App\Controller;

use App\Repository\SiteRepository;
use App\Repository\MediasRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommunicationController extends AbstractController
{
    #[Route('/communication', name: 'app_communication')]
    public function index(MediasRepository $mediasRepository, SiteRepository $siteRepository): Response
    {
        //il faut trier les informations suivant la personne connectée : site/mandat/convention
        /** @var User $user*/
        $user = $this->getUser();
        $tbSite=$siteRepository->findByUserID($user->getId());
        $ikey=0;
        foreach ($tbSite as $key => $site) {
            $tbSiteParse[$key]='Site/'.$site->getname();
            $ikey++;
        }
        $tbSiteParse[$ikey]='Groupe/DAHER';
        $Autorisation=$user->getAutorisation()->getId();
        for ($i=2; $i <= $Autorisation ; $i++) { 
            $tbAutorisation[$i]=$i;
        }
        
        return $this->render('communication/index.html.twig', [
            'medias' => $mediasRepository->myFindByUserProfile($tbAutorisation,$tbSiteParse,'','DESC'),
        ]);
    }

    #[Route('/communication/{division}', name: 'app_communication_division')]
    public function com_div(MediasRepository $mediasRepository, SiteRepository $siteRepository, $division): Response
    {        
        return $this->render('communication/index.html.twig', [
            'medias' => $mediasRepository->myFindByDivision($division,'DESC'),
        ]);
    }
}
