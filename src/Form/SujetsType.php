<?php

namespace App\Form;

use App\Entity\Sujets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SujetsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //Gestion de l'attribut périmètre
        if ($options['data']->getPerimetre()==null) {
            $perimetre['Site']='Site';
        } else {
            $getPerimetre=explode('/',$options['data']->getPerimetre());
            $perimetre[$getPerimetre[0]]=$getPerimetre[0];
        }
        $builder
            ->add('title')
            ->add('description')
            ->add('perimetre', ChoiceType::class, [
                'label'    => 'Périmètre concerné par le sujet',
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
            'data_class' => Sujets::class,
        ]);
    }
}
