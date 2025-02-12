<?php

namespace App\Form;

use App\Entity\Division;
use App\Entity\Mandat;
use App\Entity\Site;
use App\Entity\Statut;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('nom')
            ->add('prenom')
            ->add('dateAdhesion')
            ->add('tel')
            ->add('Site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('Division', EntityType::class, [
                'class' => Division::class,
                'choice_label' => 'name',
            ])
            ->add('mandat', EntityType::class, [
                'class' => Mandat::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
/*             ->add('autorisation', EntityType::class, [
                'class' => Statut::class,
                'choice_label' => 'libelle',
            ]) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
