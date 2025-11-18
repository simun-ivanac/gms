<?php

/**
 * Reset Password Controller.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TeamMember;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

/**
 * Class ResetPasswordController.
 */
#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
	use ResetPasswordControllerTrait;

	/**
	 * Constructor.
	 *
	 * @param ResetPasswordHelperInterface $resetPasswordHelper
	 * @param EntityManagerInterface $entityManager
	 */
	public function __construct(
		private ResetPasswordHelperInterface $resetPasswordHelper,
		private EntityManagerInterface $entityManager
	) {
	}

	/**
	 * Process sending the email.
	 *
	 * @param TeamMember         $teamMember Team Member entity.
	 * @param Request             $request    HTTP request.
	 * @param MailerInterface     $mailer     Mailer.
	 * @param TranslatorInterface $translator Translator.
	 *
	 * @return RedirectResponse
	 */
	#[Route('/{id<\d+>}', name: 'send_reset_email', methods: ['POST'])]
	public function sendPasswordResetEmail(
		TeamMember $teamMember,
		Request $request,
		MailerInterface $mailer,
		TranslatorInterface $translator
	): RedirectResponse {
		$token = $request->getPayload()->get('token');

		if (!$this->isCsrfTokenValid('reset_password', $token)) {
			$this->addFlash('error', 'Some security thing failed!');

			return $this->redirectToRoute(
				'team_member_edit',
				[
					'id' => $teamMember->getId(),
					'tab' => 'settings',
				],
				Response::HTTP_SEE_OTHER
			);
		}

		// Generate a signed URL and email it to the user.
		try {
			$resetToken = $this->resetPasswordHelper->generateResetToken($teamMember);
		} catch (ResetPasswordExceptionInterface $e) {
			$this->addFlash('error', sprintf(
				'%s - %s',
				$translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
				$translator->trans($e->getReason(), [], 'ResetPasswordBundle')
			));

			return $this->redirectToRoute(
				'team_member_edit',
				[
					'id' => $teamMember->getId(),
					'tab' => 'settings',
				],
				Response::HTTP_SEE_OTHER
			);
		}

		$email = (new TemplatedEmail())
			->from(new Address('info@gms.wip', 'GMS Mailer'))
			->to((string) $teamMember->getEmail())
			->subject('Your password reset request')
			->htmlTemplate('reset_password/email.html.twig')
			->context([
				'resetToken' => $resetToken,
			])
		;

		$mailer->send($email);

		// Store the token object in session for retrieval in check-email route.
		$this->setTokenObjectInSession($resetToken);

		return $this->redirectToRoute('check_email_for_password_reset', ['id' => $teamMember->getId()]);
	}

	/**
	 * Confirmation page after a user has requested a password reset.
	 *
	 * @param int $id Team Member ID.
	 *
	 * @return Response
	 */
	#[Route('/check-email/{id<\d+>}', name: 'check_email_for_password_reset')]
	public function checkEmail(int $id): Response
	{
		// Generate a fake token if the user does not exist or someone hit this page directly.
		// This prevents exposing whether or not a user was found with the given email address or not.
		if (($resetToken = $this->getTokenObjectFromSession()) === null) {
			$resetToken = $this->resetPasswordHelper->generateFakeResetToken();
		}

		// phpcs:ignore Generic.Files.LineLength.TooLong
		$this->addFlash('success', 'Email with password reset instructions is sent. It will expire in 1 hour. Please check your inbox (and spam folder).');

		return $this->redirectToRoute(
			'team_member_edit',
			[
				'id' => $id,
				'tab' => 'settings',
			],
			Response::HTTP_SEE_OTHER
		);
	}

	/**
	 * Validates and process the reset URL that the user clicked in their email.
	 *
	 * @param Request                     $request        HTTP request.
	 * @param UserPasswordHasherInterface $passwordHasher User password hasher.
	 * @param TranslatorInterface         $translator     Translator.
	 * @param string|null                 $token          Reset password token.
	 *
	 * @return Response
	 */
	#[Route('/reset/{token}', name: 'process_reset_password')]
	public function reset(
		Request $request,
		UserPasswordHasherInterface $passwordHasher,
		TranslatorInterface $translator,
		?string $token = null
	): Response {
		if ($token) {
			// We store the token in session and remove it from the URL, to avoid the URL being
			// loaded in a browser and potentially leaking the token to 3rd party JavaScript.
			$this->storeTokenInSession($token);

			return $this->redirectToRoute('process_reset_password');
		}

		$token = $this->getTokenFromSession();

		if ($token === null) {
			throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
		}

		try {
			$user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
		} catch (ResetPasswordExceptionInterface $e) {
			$this->addFlash('error', sprintf(
				'%s - %s',
				$translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, [], 'ResetPasswordBundle'),
				$translator->trans($e->getReason(), [], 'ResetPasswordBundle')
			));

			return $this->redirectToRoute('forgot_password_request');
		}

		// The token is valid; allow the user to change their password.
		$form = $this->createForm(ChangePasswordFormType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// A password reset token should be used only once, remove it.
			$this->resetPasswordHelper->removeResetRequest($token);

			// Encode(hash) the plain password, and set it.
			$plainPassword = $form->get('plainPassword')->getData();
			$user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
			$this->entityManager->flush();

			// The session is cleaned up after the password has been changed.
			$this->cleanSessionAfterReset();

			return $this->redirectToRoute('dashboard_index');
		}

		return $this->render('reset_password/reset.html.twig', [
			'resetForm' => $form,
		]);
	}
}
