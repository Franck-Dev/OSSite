<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\SiteRepository;


class GestionPerimetre
{
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