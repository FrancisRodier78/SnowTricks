<?php

namespace App\Form;

use App\Entity\Figure;
use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DocumentType1 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', TextType::class, [
                'attr' => [
                    'placeholder' => "Tapez l'URL."
                ]
            ])
            ->add('booleanImageVideo', TextType::class,  [
                'attr' => [
                    'value' => "1",
                    'hidden' => "true"
                ]
            ])
            //->add('figurePicture')
            //->add('figurePicture', TextType::class)
            //->add('figurePicture', TextareaType::class)
            //->add('figurePicture', EntityType::class, [
            //    'class' => Document::class
            //])

            //->add('figureVideo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
