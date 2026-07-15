<?php

/**
 * Plan Data Form.
 */

declare(strict_types=1);

namespace App\Form;

use App\Entity\Plan;
use App\EventSubscriber\AddIdFieldSubscriber;
use App\EventSubscriber\RemoveSubmitSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanDataFormType extends AbstractType
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
			->add('name', TextType::class)
			->add('type', ChoiceType::class, [
				'choices'  => [
					'Recurring' => 'recurring',
					'One-time' => 'one-time',
				],
				'expanded' => true,
				'multiple' => false,
			])
			->add('price', IntegerType::class)
			->add('durationInDays', IntegerType::class)
			->add('areVisitationsLimited', ChoiceType::class, [
				'choices'  => [
					'Unlimited' => false,
					'Limited' => true,
				],
				'expanded' => true,
				'multiple' => false,
			])
			->add('numOfVisitations', IntegerType::class, [
				'required' => false,
			])
			->add('timePeriod', ChoiceType::class, [
				'placeholder' => '-- set time period --',
				'placeholder_attr' => [
					'disabled' => 'disabled',
					'selected' => 'selected',
				],
				'choices'  => [
					'per day' => 'daily',
					'per week' => 'weekly',
					'per month' => 'monthly',
					'in whole duration' => 'in-total',
				],
				'required' => false,
			])
			->add('save', SubmitType::class)
			->addEventSubscriber(new AddIdFieldSubscriber($options))
			->addEventSubscriber(new RemoveSubmitSubscriber($options))
		;

		// If visitations are unlimited in plan, set number of visitations to null.
		$builder->addEventListener(
			FormEvents::SUBMIT,
			function (SubmitEvent $event): void {
				$data = $event->getData();

				if (!$data->getAreVisitationsLimited()) {
					$data->setNumOfVisitations(null);
					$data->setTimePeriod(null);
				}
			}
		);
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
			'data_class' => Plan::class,
			'formAction' => '',
			'disabled' => true,
		]);
	}
}
