<?php

namespace App\Form;

use App\Entity\Figure;
use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
