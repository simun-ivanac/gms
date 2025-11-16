<?php

/**
 * Login Subscriber.
 */

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\TeamMember;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

/**
 * Class LoginSubscriber.
 */
class LoginSubscriber implements EventSubscriberInterface
{
	/**
	 * Disallow inactive team members to login.
	 *
	 * @param CheckPassportEvent $event
	 *
	 * @return void
	 */
	public function onCheckPassportEvent(CheckPassportEvent $event): void
	{
		$passport = $event->getPassport();
		$teamMember = $passport->getUser();

		if (!$teamMember instanceof TeamMember) {
			throw new CustomUserMessageAuthenticationException('Ooh, you are unexpected user... nice try!');
		}

		if (!$teamMember->getIsActive() || $teamMember->getIsDeactivated()) {
			throw new CustomUserMessageAuthenticationException('Your account is not active. Please contact your superiors.');
		}
	}

	/**
	 * Get subscribed events.
	 *
	 * @return array
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			CheckPassportEvent::class => ['onCheckPassportEvent', 10],
		];
	}
}
