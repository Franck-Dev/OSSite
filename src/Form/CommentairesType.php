<?php

namespace App\Form;

use App\Entity\Commentaires;
use App\Entity\Sujets;
use App\Entity\user;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('body')
            /*
            ->add('createdAt')
            ->add('createdBy', EntityType::class, [
                'class' => user::class,
'choice_label' => 'id',
            ])
            ->add('sujets', EntityType::class, [
                'class' => Sujets::class,
'choice_label' => 'id',
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaires::class,
        ]);
    }
}
