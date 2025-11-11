<?php

/**
 * Login Controller.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TeamMember;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class LoginController.
 */
class LoginController extends AbstractController
{
	/**
	 * Login.
	 *
	 * @param TeamMember|null $teamMember Team member.
	 * @param AuthenticationUtils $authenticationUtils Authentication utils.
	 *
	 * @return Response HTTP response.
	 */
	#[Route(path: '/login', name: 'app_login')]
	public function login(#[CurrentUser] ?TeamMember $teamMember, AuthenticationUtils $authenticationUtils): Response
	{
		// If logged in, redirect to dashboard.
		if ($teamMember) {
			return $this->redirectToRoute('dashboard_index');
		}

		return $this->render('login/login.html.twig', [
			'lastUsername' => $authenticationUtils->getLastUsername(),
			'loginError' => $authenticationUtils->getLastAuthenticationError(),
		]);
	}

	/**
	 * Logout.
	 *
	 * @return void
	 */
	#[Route(path: '/logout', name: 'app_logout')]
	public function logout(): void
	{
		throw new \LogicException('It is not possible to logout ;)');
	}
}
