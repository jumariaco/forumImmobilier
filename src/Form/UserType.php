<?php

namespace App\Form;

use App\Entity\Membre;
use App\Entity\Partenaire;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Pseudo')
            ->add('email')
            ->add('roles')
            ->add('actif')
            // ->add ('membre', MembreType::class)
            // ->add ('partenaire', PartenaireType::class)
            // ->add('partenaire')
            // ->add('membre')
            // ->add('abonnement')
            // ->add('abonnes')
            ->add('acceptationCgu')
            ->add('newsletter')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
