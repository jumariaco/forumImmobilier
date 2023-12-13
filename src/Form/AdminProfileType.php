<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminProfileType extends AbstractType
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
            ->add('email')
            ->add('Pseudo')
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
            ->add('actif', CheckboxType::class, [
                'data' => true
            ])
            ->add('acceptationCgu', CheckboxType::class, [
                'required' => true,
                'invalid_message' => 'Veuillez accepter les CGU',
            ])
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
