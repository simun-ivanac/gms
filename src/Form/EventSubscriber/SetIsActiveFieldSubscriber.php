<?php

/**
 * Field "isActive" Event Subscriber.
 */

declare(strict_types=1);

namespace App\Form\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class SetIsActiveFieldSubscriber.
 */
class SetIsActiveFieldSubscriber implements EventSubscriberInterface
{
	/**
	 * Get subscribed events.
	 *
	 * @return array<string, string>
	 */
	public static function getSubscribedEvents(): array
	{
		return [FormEvents::SUBMIT => 'onSubmitData'];
	}

	/**
	 * On Submit event, set "isActive" field to false.
	 *
	 * @param FormEvent $event Form event.
	 *
	 * @return void
	 */
	public function onSubmitData(FormEvent $event): void
	{
		$member = $event->getData();
		$member->setIsActive(false);
		$event->setData($member);
	}
}
