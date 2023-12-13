<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('Pseudo')
            ->add('actif')
            ->add('acceptationCgu')
            ->add('newsletter')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('deletedAt')
            ->add('partenaire')
            ->add('membre')
            ->add('abonnement')
            ->add('abonnes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
