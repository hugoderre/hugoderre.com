<?php

namespace App\Form\Type\Email;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewsletterType extends AbstractType {

	private $translator;

	public function __construct(TranslatorInterface $translator)
	{
		$this->translator = $translator;
	}

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
                'label' => $this->translator->trans('Envoyer'),
                'attr'  => [
                    'class' => 'btn btn-primary',
                ],
            ]);
    }
}