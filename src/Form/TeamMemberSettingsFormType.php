<?php

/**
 * Team Member Settings Form.
 */

declare(strict_types=1);

namespace App\Form;

use App\Entity\TeamMember;
use App\Service\TeamMemberSettingsInCookie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TeamMemberSettingsFormType.
 */
class TeamMemberSettingsFormType extends AbstractType
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
			->add('teamUserEdit', CheckboxType::class, [
				'label' => 'Allow editing user details?',
				'mapped' => false,
				'required' => false,
				'attr' => $options[TeamMemberSettingsInCookie::IS_EDITING_ALLOWED] ? ['checked' => 'checked'] : [],
				'false_values' => [
					0,
					false,
					null,
					'0',
					'false',
				]
			])
			->add('update', SubmitType::class)
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
			TeamMemberSettingsInCookie::IS_EDITING_ALLOWED => false,
		]);
	}
}
