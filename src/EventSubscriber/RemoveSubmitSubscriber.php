<?php

/**
 * Remove Submit Button Subscriber.
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;

class RemoveSubmitSubscriber implements EventSubscriberInterface
{
	/**
	 * RemoveSubmitSubscriber constructor.
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
			FormEvents::PRE_SET_DATA => 'removeSubmitField',
		];
	}

	/**
	 * Remove submit button if form is disabled for editing.
	 *
	 * @param object $event Form event.
	 *
	 * @return void
	 */
	public function removeSubmitField(object $event): void
	{
		$form = $event->getForm();
		$formAction = $this->options['formAction'] ?? '';
		$isDisabled = $this->options['disabled'] ?? true;

		if ($formAction !== 'edit' || !$isDisabled) {
			return;
		}

		$form->remove('save');
	}
}
