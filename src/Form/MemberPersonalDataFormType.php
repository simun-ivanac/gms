<?php

/**
 * Member Personal Data Form.
 */

declare(strict_types=1);

namespace App\Form;

use App\Entity\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class MemberPersonalDataFormType.
 */
class MemberPersonalDataFormType extends AbstractType
{
	/**
	 * Generate form.
	 *
	 * @param FormBuilderInterface $builder Form builder.
	 * @param array<string, mixed> $options Form options.
	 *
	 * @return void
	 */
	// phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('photoFile', FileType::class, [
				'required' => false,
				'mapped' => false,
				'help' => 'Max size: 2MB.',
				'constraints' => [
					new File(
						maxSize: '2M',
						extensions: [
							'jpg',
							'jpeg',
							'png',
							'gif',
							'webp',
							'bmp',
							'svg',
							'tiff',
						],
					)
				],
			])
			->add('firstName', TextType::class)
			->add('lastName', TextType::class)
			->add('dateOfBirth', DateType::class)
			->add('gender', ChoiceType::class, [
				'choices'  => [
					'Male' => 'male',
					'Female' => 'female',
					'Prefer not to say' => 'null',
				],
				'expanded' => true,
				'multiple' => false,
			])
			->add('email', EmailType::class, [
				'required' => false,
			])
			->add('phoneNumber', TelType::class, [
				'required' => false,
				'help' => 'e.g. +385115559999',
			])
			->add('pin', IntegerType::class)
			->add('save', SubmitType::class)
		;

		// If in edit mode, show member ID.
		if ($options['formAction'] === 'edit') {
			$builder->add('id', TextType::class, [
				'mapped' => false,
				'attr' => [
					'value' => isset($options['data']) ? ($options['data'])->getId() : null,
					'disabled' => true,
				],
			]);
		}

		// Remove save button if editing member is not allowed.
		if ($options['formAction'] === 'edit' && $options['disabled']) {
			$builder->remove('save');
		}
	}

	/**
	 * Configure options.
	 *
	 * @param OptionsResolver $resolver Options resolver.
	 *
	 * @return void
	 */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Member::class,
			'formAction' => '',
			'disabled' => true,
		]);
	}
}
