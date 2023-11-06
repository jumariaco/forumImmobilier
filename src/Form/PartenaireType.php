<?php

namespace App\Form;

use App\Entity\Partenaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartenaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', ['purify_html' => true])
            ->add('prenom', ['purify_html' => true])
            ->add('societe', ['purify_html' => true])
            ->add('siret', ['purify_html' => true])
            ->add('metier', ['purify_html' => true])
            ->add('telephone', ['purify_html' => true])
            ->add('emailContact', ['purify_html' => true])
            ->add('departement')
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
            ->add('titre', EntityType::class, [
                'class' => Titre::class,
                'choice_label' => 'nom',
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.nom', 'ASC');
                }
            ])
            ->add ('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partenaire::class,
        ]);
    }
}
