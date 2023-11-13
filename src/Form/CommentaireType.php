<?php

namespace App\Form;

use App\Entity\Commentaire;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
Use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('user', CheckboxType::class, [
            //     'mapped' => false
            // ])
            ->add('contenu', CKEditorType::class, [
                'label' => 'Poster un nouveau commentaire : ',
                'purify_html' => true])
            ->add('choixRetenu', HiddenType::class, [
                'mapped' => false
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'label' => 'Insérer une image :',
            ])
            // ->add('publication', EntityType::class, [
            //     'class' => Publication::class,
            //     'choice_label' => 'titre',
            //     'placeholder' => 'Sélectionnez la publication'
            // ])
            
            ->add('parentid', HiddenType::class, [
                'mapped' => false
            ])
            ->add ('envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
