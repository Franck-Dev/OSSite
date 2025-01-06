<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dd($options);
        if ($options['priority']==1) {
            $builder
            ->add('contenu', TextareaType::class, [
                'empty_data' => '',
                'required'   => true,
                'label'   => 'Question'
            ])
            ->add('reponse', TextareaType::class, [
                'empty_data' => '',
                'required'   => false,
                'disabled'   => true
            ])
        ;
        } else {
            $builder
            ->add('contenu', TextareaType::class, [
                'empty_data' => '',
                'required'   => true,
                'label'   => 'Question',
                'disabled'   => true
            ])
            ->add('reponse', TextareaType::class, [
                'empty_data' => '',
                'required'   => false
            ])
        ;
        }
        
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
