<?php

namespace App\Form;

use App\Entity\Domaine;
use App\Entity\Publication;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
Use Vich\UploaderBundle\Form\Type\VichImageType;

class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('contenu', CKEditorType::class, ['purify_html' => true])
            // ->add('statut', null, [
            //     'disabled' => $options['is_membre'],
            // ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'label' => 'Image Publication',
            ])
            ->add('domaines', EntityType::class, [
                'class' => Domaine::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'form-scrollable-checkboxes',
                ],
                'by_reference' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.nom', 'ASC');
                }
            ])
            // modifier pour ne sélectionner automatiquement l'user connecté
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'pseudo',
            //     'required' => true,
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er->createQueryBuilder('u')
            //             ->orderBy('u.id', 'ASC');
            //     }
                
            // ])
            ;  
            // a tester pour sélectionner automatiquement l'user connecté
            // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            //     $form = $event->getForm();
            //     $data = $event->getData();
    
            //     // Si vous avez un utilisateur connecté, attribuez son ID au champ 'user'
            //     if ($this->security->getUser()) {
            //         $data->setUser($this->security->getUser());
            //     }
            // });    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
            'is_membre' => false, // Par défaut, le formulaire est configuré pour un utilisateur non MEMBRE
        ]);
    }
}
