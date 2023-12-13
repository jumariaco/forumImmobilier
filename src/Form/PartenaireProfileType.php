<?php

namespace App\Form;


use App\Entity\User;
use App\Entity\Domaine;
use App\Entity\Titre;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PartenaireProfileType extends AbstractType
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher=$hasher;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $hasher=$this->hasher;
        $builder
            ->add ('email')
            ->add ('pseudo')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => ['attr'=> [
                    'class' => 'password-field',
                    'autocomplete' => 'new-password'
                ]],
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' =>['label' => 'Confirmer le mot de passe'],
                'required' => true,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($hasher) {
                $user=$event->getData();
                if (!$user) {
                    return;
                }
                $password=$user->getPassword();
                $password=$hasher->hashPassword($user, $password);
                $user->setPassword($password);

                $event->setData($user);
            })
            ->add ('partenaire', PartenaireType::class, [
                'label' => false
            ])
            ->add ('acceptationCgu', CheckboxType::class, [
                'required' => true,
                'invalid_message' => 'Veuillez accepter les CGU',
            ])
            ->add ('newsletter')
            ->add('actif', HiddenType::class, [
                'data' => false,
            ]);
          
            // ->add('nom')
            // ->add('prenom')
            // ->add('societe')
            // ->add('siret')
            // ->add('metier')
            // ->add('telephone')
            // ->add('emailContact')
            // ->add('departement')
            // ->add('domaines', Domaine::class, [
            //     'choice_label' => 'nom',
            //     'multiple' => true,
            //     'expanded' => true,
            //     'attr' => [
            //         'class' => 'form-scrollable-checkboxes',
            //     ],
            //     'by_reference' => false,
            //     'query_builder' => function ($er) {
            // ->add('nom')
            // ->add('prenom')
            // ->add('societe')
            // ->add('siret')
            // ->add('metier')
            // ->add('telephone')
            // ->add('emailContact')
            // ->add('departement')
            // ->add('domaines', Domaine::class, [
            //     'choice_label' => 'nom',
            //     'multiple' => true,
            //     'expanded' => true,
            //     'attr' => [
            //         'class' => 'form-scrollable-checkboxes',
            //     ],
            //     'by_reference' => false,
            //     'query_builder' => function ($er) {
            //         return $er->createQueryBuilder('d')
            //             ->orderBy('d.nom', 'ASC');
            //     }
            // ])
            // ->add('titre', Titre::class, [
            //     'choice_label' => 'nom',
            //     'query_builder' => function ($er) {
            //         return $er->createQueryBuilder('t')
            //             ->orderBy('t.nom', 'ASC');
            //     }
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
