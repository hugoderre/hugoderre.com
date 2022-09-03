<?php

namespace App\Form\Type\Email;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class NewsletterType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
				'label' => false,
                'attr'  => [
                    'placeholder' => 'Email',
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'form-group',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr'  => [
                    'class' => 'btn btn-primary',
                ],
            ]);
    }
}