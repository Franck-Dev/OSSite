<?php

namespace App\Form;

use App\Entity\Medias;
use App\Entity\Statut;
use App\Entity\TypeMedias;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //Gestion de l'attribut périmètre
        if ($options['data']->getPerimetre()==null) {
            $perimetre['Site']='Site';
            $reqPer=true;
        } else {
            $getPerimetre=explode('/',$options['data']->getPerimetre());
            $perimetre[$getPerimetre[0]]=$getPerimetre[0];
            $reqPer=false;
        }
        //Gestion de l'attribut fichier obligatoire
        if ($options['data']->getFichierPath()==null) {
            $reqFic=true;
            $visFic=false;
            $value='';
        } else {
            $reqFic=false;
            $visFic=true;
            $value=$options['data']->getFichierPath();
        }
        //Gestion de l'attribut image
        if ($options['data']->getImage()==null) {
            $visImg=false;
        } else {
            $visImg=true;
        }
        //dd($visImg);
        $builder
            ->add('nom')
            ->add('preface', TextareaType::class)
            ->add('contenu', TextareaType::class,[
                'required' => true
            ])
            ->add('fichier', FileType::class,[
                'mapped' => true,
                'required' => $reqFic,
                'disabled' => $visFic,
                'attr' => [
                    'value' => $value
                ]
            ])
            ->add('couverture', FileType::class,[
                'mapped' => false,
                'required' => $reqPer,
                'disabled' => $visImg
            ])
            ->add('type', EntityType::class, [
                'class' => TypeMedias::class,
                'choice_label' => 'libelle',
            ])
            ->add('visibilite', EntityType::class, [
                'class' => Statut::class,
                'choice_label' => 'libelle',
            ])
            ->add('perimetre', ChoiceType::class, [
                'label'    => 'Périmètre concerné par le média',
                'mapped' => false,
                'preferred_choices' => $perimetre,
                'choices'  => [
                    'Site' => 'Site',
                    'Division' => 'Division',
                    'Convention' => 'Convention',
                    'Groupe' => 'Groupe'
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medias::class,
        ]);
    }
}
