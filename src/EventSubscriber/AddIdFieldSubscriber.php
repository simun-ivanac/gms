<?php

/**
 * Add ID Field Subscriber.
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;

class AddIdFieldSubscriber implements EventSubscriberInterface
{
	/**
	 * AddIdFieldSubscriber constructor.
	 *
	 * @param array $options Form options.
	 */
	public function __construct(
		private array $options = [],
	) {
	}

	/**
	 * Get subscribed events.
	 *
	 * @return array
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			FormEvents::PRE_SET_DATA => 'addIdField',
		];
	}

	/**
	 * Add disabled ID field to form if in edit mode.
	 *
	 * @param object $event Form event.
	 *
	 * @return void
	 */
	public function addIdField(object $event): void
	{
		$form = $event->getForm();
		$formAction = $this->options['formAction'] ?? '';

		if ($formAction !== 'edit') {
			return;
		}

		$form->add('id', TextType::class, [
			'mapped' => false,
			'attr' => [
				'value' => isset($this->options['data']) ? ($this->options['data'])->getId() : null,
				'disabled' => true,
			],
		]);
	}
}
