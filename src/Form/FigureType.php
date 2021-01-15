<?php

namespace App\Form;

use App\Entity\Figure;
use App\Entity\Groupe;
use App\Form\DocumentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
            ->add('picture', CollectionType::class, [
                'entry_type' => DocumentType1::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('video', CollectionType::class, [
                'entry_type' => DocumentType2::class,
                'allow_add' => true,
                'allow_delete' => true,
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
