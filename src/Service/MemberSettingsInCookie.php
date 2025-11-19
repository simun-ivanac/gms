<?php

/**
 * Temporary Member Settings that are saved in cookie.
 */

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Class MemberSettingsInCookie.
 */
class MemberSettingsInCookie
{
	/**
	 * Fixed part of cookie name.
	 * Whole part: 'user_' . $memberId.
	 */
	private const COOKIE_NAME = 'user_';

	/**
	 * Member cookie.
	 */
	private array $memberCookie = [];

	/**
	 * Settings key saved in cookie.
	 */
	public const IS_EDITING_ALLOWED = 'isEditingAllowed';

	/**
	 * MemberSettingsInCookie constructor.
	 *
	 * @param RequestStack $requestStack Request stack.
	 */
	public function __construct(private RequestStack $requestStack)
	{
	}

	/**
	 * Get cookie.
	 *
	 * @param int $memberId Member ID.
	 *
	 * @return array
	 */
	public function getCookie(int $memberId): array
	{
		if (!empty($this->memberCookie)) {
			return $this->memberCookie;
		}

		$request = $this->requestStack->getCurrentRequest();

		if (!$request) {
			return $this->memberCookie;
		}

		$cookie = $request->cookies->get(self::COOKIE_NAME . $memberId);

		$this->memberCookie = $cookie ? (json_decode($cookie, true) ?? []) : [];

		return $this->memberCookie;
	}

	/**
	 * Update cookie.
	 *
	 * @param int      $memberId Member ID.
	 * @param array    $newData  New data.
	 * @param Response $response Response.
	 *
	 * @return bool
	 */
	public function updateCookie(int $memberId, array $newData, Response $response): bool
	{
		// If user editing is disallowed, delete cookie.
		if (!$newData[MemberSettingsInCookie::IS_EDITING_ALLOWED]) {
			return $this->deleteCookie($memberId, $response);
		}

		// Update cookie.
		$cookie = $this->getCookie($memberId);
		$cookie = array_merge($this->memberCookie, $newData);
		$this->memberCookie = $cookie;

		$newCookie = Cookie::create(self::COOKIE_NAME . $memberId, json_encode($cookie))
			->withExpires(strtotime('+1 hour'))
			->withPath('/')
			->withSecure(true)
			->withHttpOnly(true)
			->withSameSite('lax');

		$response->headers->setCookie($newCookie);

		return true;
	}

	/**
	 * Delete cookie.
	 *
	 * @param int      $memberId Member ID.
	 * @param Response $response Response.
	 *
	 * @return bool
	 */
	public function deleteCookie(int $memberId, Response $response): bool
	{
		if (!empty($this->memberCookie)) {
			$this->memberCookie = [];
		}

		$response->headers->clearCookie(self::COOKIE_NAME . $memberId, '/');

		return true;
	}

	/**
	 * Get cookie key value.
	 *
	 * @param int    $memberId Member ID.
	 * @param string $key      Key.
	 * @param mixed  $default  Default value.
	 *
	 * @return mixed
	 */
	public function getCookieKey(int $memberId, string $key, mixed $default = null): mixed
	{
		$cookie = $this->getCookie($memberId);

		return $cookie[$key] ?? $default;
	}
}
