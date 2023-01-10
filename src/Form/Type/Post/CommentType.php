<?php

namespace App\Form\Type\Post;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CommentType extends AbstractType {

	private $translator;

	public function __construct(TranslatorInterface $translator)
	{
		$this->translator = $translator;
	}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('authorName', TextType::class, [
                'label' => $this->translator->trans("Nom d'utilisateur"),
                'attr'  => [
                    'placeholder' => '',
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'form-group',
                ],
            ])
            ->add('authorEmail', EmailType::class, [
                'label' => 'Email',
                'attr'  => [
                    'placeholder' => '',
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'form-group',
                ],
            ])
			->add('authorWebsite', UrlType::class, [
                'label' => $this->translator->trans('Site web'),
                'attr'  => [
                    'placeholder' => '',
                    'class' => 'form-control',
                ],
                'row_attr' => [
                    'class' => 'form-group',
                ],
				'required' => false,
            ])
			->add('country', CheckboxType::class, [ // Honneypot :)
				'label' => false,
				'attr'  => [
					'placeholder' => '',
					'class' => 'form-control',
					'tabindex' => '-1',
				],
				'row_attr' => [
					'class' => 'form-group form-check-country',
				],
				'mapped' => false,
				'required' => false,
			])
            ->add('content', TextareaType::class, [
                'label' => $this->translator->trans('Votre commentaire'),
                'attr'  => [
                    'placeholder' => '',
                    'class' => 'form-control',
					'rows' => '5',
                ],
                'row_attr' => [
                    'class' => 'form-group',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $this->translator->trans('Envoyer'),
                'attr'  => [
                    'class' => 'btn btn-primary',
                ],
            ]);
    }
}