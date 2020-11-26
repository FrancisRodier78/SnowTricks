<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => "Tapez votre prénom."
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => "Tapez votre nom."
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email ',
                'attr' => [
                    'placeholder' => "Tapez votre email."
                ]
            ])
            ->add('introduction', TextType::class, [
                'label' => 'Introduction',
                'attr' => [
                    'placeholder' => "C'est le moment de vous présenter."
                ]
            ])
            ->add('hash', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => "Tapez un mot de passe."
                ]
            ])
            ->add('passwordConfirme', PasswordType::class, [
                'label' => 'Confirmez le mot de passe',
                'attr' => [
                    'placeholder' => "Retapez votre mot de passe."
                ]
            ])
            ->add('avatar', UrlType::class, [
                'label' => 'Photo de profile',
                'attr' => [
                    'placeholder' => "Tapez l'url de votre avatar."
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
