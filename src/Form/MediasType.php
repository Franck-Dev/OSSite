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
        $builder
            ->add('nom')
            ->add('preface', TextareaType::class)
            ->add('fichier', FileType::class,[
                'mapped' => false
            ])
            ->add('couverture', FileType::class,[
                'mapped' => false,
                'required' => false
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
