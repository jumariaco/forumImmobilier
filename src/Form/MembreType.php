<?php

namespace App\Form;

use App\Entity\Domaine;
use App\Entity\Membre;
use App\Entity\Titre;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MembreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', EntityType::class, [
                'class' => Titre::class,
                'choice_label' => 'nom',
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.nom', 'ASC');
                }
            ])
            ->add('nom', ['purify_html' => true])
            ->add('prenom', ['purify_html' => true])
            ->add('metier', ['purify_html' => true])
            ->add('domaines', EntityType::class, [
                'class' => Domaine::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-scrollable-checkboxes',
                ],
                'by_reference' => false,
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.nom', 'ASC');
                }
            ])
            ->add ('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
