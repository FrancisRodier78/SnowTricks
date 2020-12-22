<?php

namespace App\Form;

use App\Entity\Figure;
use App\Entity\Groupe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('figureName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => "Tapez le nom de la figure."
                ]
            ])
            //->add('slug')
            ->add('description', TextareaType::class, [
                'label' => 'Description ',
                'attr' => [
                    'placeholder' => "Tapez la description de la figure."
                ]
            ])
            ->add('imageDefaut', FileType::class, [
//            ->add('imageDefaut', TextType::class, [
                    'label' => 'Image',
                'attr' => [
                    'placeholder' => "Tapez l'adresse de l'image de la figure."
                ]
            ])
            //->add('creationDate')
            //->add('modifDate')
            //->add('authorId')
            ->add('groupe', EntityType::class, [
                'class' => Groupe::class,
                'choice_label' => 'groupeName' 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class,
        ]);
    }
}
