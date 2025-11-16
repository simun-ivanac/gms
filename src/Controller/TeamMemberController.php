<?php

/**
 * Team Member Controller.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TeamMember;
use App\Form\TeamMemberPersonalDataFormType;
use App\Form\TeamMemberPasswordFormType;
use App\Form\TeamMemberSettingsFormType;
use App\Repository\TeamMemberRepository;
use App\Service\ImageUploader;
use App\Service\TeamMemberSettingsInCookie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class TeamMemberController.
 */
final class TeamMemberController extends AbstractController
{
	/**
	 * Team Members main view.
	 *
	 * @param TeamMemberRepository $repository Team Member repository.
	 *
	 * @return Response HTTP response.
	 */
	#[Route('/team', name: 'team_member_index', methods: ['GET'])]
	public function index(TeamMemberRepository $repository): Response
	{
		return $this->render('team_member/index.html.twig', [
			'teamMembers' => $repository->findLatest([
				'perPage' => 15,
				'order' => 'DESC',
			]),
		]);
	}

	/**
	 * Register new team member.
	 *
	 * @param Request                     $request            HTTP request.
	 * @param UserPasswordHasherInterface $userPasswordHasher User password hasher.
	 * @param EntityManagerInterface      $entityManager      Entity manager.
	 * @param ImageUploader               $imageUploader      Image Uploader.
	 *
	 * @return Response HTTP response.
	 */
	#[Route('/team/member/new', name: 'team_member_new', methods: ['GET', 'POST'])]
	public function new(
		Request $request,
		EntityManagerInterface $entityManager,
		ImageUploader $imageUploader
	): Response {
		$teamMember = new TeamMember();
		$newTeamMemberForm = $this->createForm(TeamMemberPersonalDataFormType::class, $teamMember, ['formAction' => 'new', 'disabled' => false]);
		$newTeamMemberForm->handleRequest($request);

		if ($newTeamMemberForm->isSubmitted() && $newTeamMemberForm->isValid()) {
			$imageFile = $newTeamMemberForm->get('photoFile')->getData();

			if ($imageFile) {
				try {
					$imageFileName = $imageUploader->uploadFile($imageFile);
					$teamMember->setPhotoFile($imageFile);
					$teamMember->setPhotoFilename($imageFileName);
				} catch (FileException $e) {
					$this->addFlash('error', $e->getMessage());

					return $this->redirectToRoute('team_member_new');
				}
			}

			$entityManager->persist($teamMember);
			$entityManager->flush();

			$this->addFlash('success', 'New team member created successfully!');

			return $this->redirectToRoute(
				'team_member_edit',
				['id' => $teamMember->getId()],
				Response::HTTP_SEE_OTHER
			);
		}

		return $this->render('team_member/new.html.twig', [
			'teamMember' => $teamMember,
			'personalDataForm' => $newTeamMemberForm,
		]);
	}

	/**
	 * Edit existing Member.
	 *
	 * @param TeamMember             $teamMember    Member entity.
	 * @param Request                $request       HTTP request.
	 * @param EntityManagerInterface $entityManager Entity Manager.
	 *
	 * @return Response HTTP response.
	 */
	#[Route('/team/member/{id<\d+>}', name: 'team_member_edit', methods: ['GET', 'POST'])]
	public function edit(
		TeamMember $teamMember,
		Request $request,
		EntityManagerInterface $entityManager,
		ImageUploader $imageUploader,
		TeamMemberSettingsInCookie $teamMemberSettingsInCookie,
	): Response {
		$teamMemberId = $teamMember->getId();
		$isEditingAllowed = $teamMemberSettingsInCookie->getCookieKey($teamMemberId, TeamMemberSettingsInCookie::IS_EDITING_ALLOWED, false);

		// Personal data form.
		$personalDataForm = $this->createForm(
			TeamMemberPersonalDataFormType::class,
			$teamMember,
			['formAction' => 'edit', 'disabled' => !$isEditingAllowed]
		);

		$personalDataForm->handleRequest($request);

		if ($personalDataForm->isSubmitted() && $personalDataForm->isValid()) {
			$imageFile = $personalDataForm->get('photoFile')->getData();

			if ($imageFile) {
				try {
					$oldImageFilename = $teamMember->getPhotoFilename();
					$imageFileName = $imageUploader->uploadFile($imageFile);

					$teamMember->setPhotoFile($imageFile);
					$teamMember->setPhotoFilename($imageFileName);

					$imageUploader->deleteFile($oldImageFilename);
				} catch (FileException $e) {
					$this->addFlash('error', $e->getMessage());

					return $this->redirectToRoute('team_member_new');
				}
			}

			$entityManager->persist($teamMember);
			$entityManager->flush();

			$this->addFlash('success', 'Team member updated successfully!');

			return $this->redirectToRoute(
				'team_member_edit',
				['id' => $teamMemberId],
				Response::HTTP_SEE_OTHER
			);
		}

		// Settings form.
		$settingsForm = $this->createForm(
			TeamMemberSettingsFormType::class,
			$teamMember,
			[TeamMemberSettingsInCookie::IS_EDITING_ALLOWED => $isEditingAllowed],
		);

		$settingsForm->handleRequest($request);

		if ($settingsForm->isSubmitted() && $settingsForm->isValid()) {
			$allowUserEdit = $settingsForm->get('teamUserEdit')->getData();

			$response = $this->redirectToRoute(
				'team_member_edit',
				[
					'id' => $teamMemberId,
					'tab' => 'settings',
				],
				Response::HTTP_SEE_OTHER
			);

			$teamMemberSettingsInCookie->updateCookie(
				$teamMemberId,
				[TeamMemberSettingsInCookie::IS_EDITING_ALLOWED => $allowUserEdit],
				$response
			);

			$this->addFlash('success', 'Settings saved!');

			return $response;
		}

		return $this->render('team_member/edit.html.twig', [
			'teamMember' => $teamMember,
			'personalDataForm' => $personalDataForm,
			'settingsForm' => $settingsForm,
		]);
	}

	/**
	 * Deactivate Team Member.
	 *
	 * @param TeamMember             $teamMember    Member entity.
	 * @param Request                $request       HTTP request.
	 * @param EntityManagerInterface $entityManager Entity Manager.
	 *
	 * @return Response HTTP response.
	 */
	#[Route(path: '/member/deactivate/{id<\d+>}', name: 'team_member_deactivate', methods: ['POST'])]
	public function deactivate(TeamMember $teamMember, Request $request, EntityManagerInterface $entityManager): Response
	{
		$token = $request->getPayload()->get('token');

		if (!$this->isCsrfTokenValid('deactivate', $token)) {
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

		$teamMember->setIsDeactivated(true);
		$entityManager->persist($teamMember);
		$entityManager->flush();

		$this->addFlash('success', 'Team member deactivated successfully!');

		return $this->redirectToRoute(
			'team_member_edit',
			[
				'id' => $teamMember->getId(),
				'tab' => 'settings',
			],
			Response::HTTP_SEE_OTHER
		);
	}

	/**
	 * Activate Team Member.
	 *
	 * @param TeamMember             $teamMember    Team Member entity.
	 * @param Request                $request       HTTP request.
	 * @param EntityManagerInterface $entityManager Entity Manager.
	 *
	 * @return Response HTTP response.
	 */
	#[Route(path: '/team/member/activate/{id<\d+>}', name: 'team_member_activate', methods: ['POST'])]
	public function activate(TeamMember $teamMember, Request $request, EntityManagerInterface $entityManager): Response
	{
		$token = $request->getPayload()->get('token');

		if (!$this->isCsrfTokenValid('activate', $token)) {
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

		$teamMember->setIsDeactivated(false);
		$entityManager->persist($teamMember);
		$entityManager->flush();

		$this->addFlash('success', 'Team member activated successfully!');

		return $this->redirectToRoute(
			'team_member_edit',
			[
				'id' => $teamMember->getId(),
				'tab' => 'settings',
			],
			Response::HTTP_SEE_OTHER
		);
	}

	/**
	 * Delete Team Member.
	 *
	 * @param TeamMember             $teamMember    Member entity.
	 * @param EntityManagerInterface $entityManager Entity Manager.
	 *
	 * @return Response HTTP response.
	 */
	#[Route(path: '/team/member/delete/{id<\d+>}', name: 'team_member_delete', methods: ['POST'])]
	public function delete(TeamMember $teamMember, Request $request, EntityManagerInterface $entityManager): Response
	{
		$token = $request->getPayload()->get('token');

		if (!$this->isCsrfTokenValid('delete', $token)) {
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

		$entityManager->remove($teamMember);
		$entityManager->flush();

		$this->addFlash('success', 'Team member deleted successfully!');

		return $this->redirectToRoute('team_member_index', [], Response::HTTP_SEE_OTHER);
	}
}
