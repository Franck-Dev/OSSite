<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    /**
     * index    Renvoie la page d'accueuil du site
     *
     * @return Response
     */
    #[Route('/', name: 'app_home')]    
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * mentions Renvoie la page des Mentions Légales
     *
     * @return Response
     */
    #[Route('/pages/mentions', name: 'app_mentions')]        
    public function mentions(): Response
    {
        $page=['name'=> 'Mentions Légales','DateUp' => "2024-03-01"];
        $chapters=[['name' => 'Editeur du site', 'texte' => "CGT DAHER\nAéroport TLP\n65290 LOUEY"],
        ['name' => 'Directeur de publication', 'texte' => "Lies TAOUCHE Coordinateur CGT DAHER"],
        ['name' => 'Conception', 'texte' => "Franck DARTOIS\n65290 LOUEY\nf.dartois@daher.com"],
        ['name' => 'Hébergement', 'texte' => "HOSTINGER INTERNATIONAL LTD\n61 Lordou Vironos Street\n6023 Larnaca, Chypre"],
        ['name' => 'Accès au site', 'texte' => "Le Site est accessible en tout endroit, 7j/7, 24h/24 sauf cas de force majeure,\n interruption programmée ou non et pouvant découlant d’une nécessité de maintenance.\n
        En cas de modification, interruption ou suspension du Site, l'Editeur ne saurait être tenu responsable."],
        ['name' => 'Collecte des données', 'texte' => "Le Site assure à l'Utilisateur une collecte et un traitement d'informations\n personnelles dans le respect de la vie privée conformément à la loi n°78-17 du 6 janvier 1978 relative à l'informatique,\n aux fichiers et aux libertés.
        En vertu de la loi Informatique et Libertés, en date du 6 janvier 1978, l'Utilisateur dispose d'un droit d'accès,\n de rectification, de suppression et d'opposition de ses données personnelles.\n L'Utilisateur exerce ce droit :\n
        ·         par mail à l'adresse email profil@cgt-daher.com\n
        ·         via un formulaire de contact\n
        ·         via son espace personnel\n
        \n
        Toute utilisation, reproduction, diffusion, commercialisation, modification de toute ou partie du Site,\n sans autorisation de l’Editeur est prohibée et pourra entraînée des actions et poursuites judiciaires\n telles que notamment prévues par le Code de la propriété intellectuelle et le Code civil."],];
        
        return $this->render('pages/mentions.html.twig', [
            'page' => $page,
            'chapters' => $chapters,
        ]);
    }
}
