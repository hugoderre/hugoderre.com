<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'Titre',
            'attr'  => [
                'placeholder' => '',
                'class' => 'form-control',
            ],
            'row_attr' => [
                'class' => 'form-group',
            ],
        ]);
        $builder->add('slug', TextType::class, [
            'label' => 'Slug',
            'attr'  => [
                'placeholder' => '',
                'class' => 'form-control',
            ],
            'row_attr' => [
                'class' => 'form-group',
            ],
            'required' => false,
        ]);
        $builder->add('content', TextareaType::class, [
            'label' => 'Contenu',
            'attr'  => [
                'placeholder' => '',
                'class' => 'form-control',
            ],
            'row_attr' => [
                'class' => 'form-group',
            ],
            'required' => false,
        ]);
        $builder->add('save', SubmitType::class, [
            'label' => 'Publier',
            'attr'  => [
                'class' => 'btn btn-primary',
            ],
            'mapped' => false,
        ]);
    }
}
