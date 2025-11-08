<?php

/**
 * Team Member Personal Data Form.
 */

declare(strict_types=1);

namespace App\Form;

use App\Entity\TeamMember;
use App\Entity\TeamMemberRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
 * Class TeamMemberPersonalDataFormType.
 */
class TeamMemberPersonalDataFormType extends AbstractType
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
			->add('email', EmailType::class)
			->add('phoneNumber', TelType::class, [
				'help' => 'e.g. +385115559999',
			])
			->add('pin', IntegerType::class)
			->add('teamRoles', EntityType::class, [
				'class' => TeamMemberRole::class,
				'choice_label' => function (TeamMemberRole $role): string {
					return ucfirst($role->getRole());
				},
				'expanded' => true,
				'multiple' => true,
			])
			->add('save', SubmitType::class)
		;
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
			'data_class' => TeamMember::class,
		]);
	}
}
