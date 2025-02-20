<?php

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'empty_data' => '',
                'required'   => false
            ])
            ->add('email', EmailType::class, [
                'empty_data' => '',
                'required'   => true
            ])
            ->add('message', TextareaType::class, [
                'empty_data' => '',
                'required'   => true
            ])
            ->add('autre', HiddenType::class, [
                'empty_data' => 'Test',
                'required'   => false
            ])
            ->add('honeyPot', RangeType::class, [
                'attr' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 2,
                    'list' => 'markers'
                ],
                'label' => 'Qui êtes-vous? Êtes-vous un robot ?',
                'label_attr' => [
                    'id' => 'label_honeyPot',
                    'value' => 'test'
                ],
                'data' => 0
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn-primary btn d-none disabled'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
