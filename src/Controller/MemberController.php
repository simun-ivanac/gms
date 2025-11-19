<?php

/**
 * Member Controller.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberPersonalDataFormType;
use App\Form\MemberSettingsFormType;
use App\Repository\MemberRepository;
use App\Service\ImageUploader;
use App\Service\MemberSettingsInCookie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class MemberController.
 */
final class MemberController extends AbstractController
{
	/**
	 * Members main view.
	 *
	 * @param MemberRepository $repository Member repository.
	 *
	 * @return Response HTTP response.
	 */
	#[Route('/members', name: 'member_index', methods: ['GET'])]
	public function index(MemberRepository $repository): Response
	{
		return $this->render('member/index.html.twig', [
			'members' => $repository->findLatest([
				'perPage' => 15,
				'order' => 'DESC',
			]),
		]);
	}

	/**
	 * New Member.
	 *
	 * @param Request                $request       HTTP request.
	 * @param EntityManagerInterface $entityManager Entity Manager.
	 * @param ImageUploader          $imageUploader Image Uploader.
	 *
	 * @return Response HTTP response.
	 */
	#[Route('/member/new', name: 'member_new', methods: ['GET', 'POST'])]
	public function new(Request $request, EntityManagerInterface $entityManager, ImageUploader $imageUploader): Response
	{
		$member = new Member();
		$newMemberform = $this->createForm(MemberPersonalDataFormType::class, $member, ['formAction' => 'new', 'disabled' => false]);
		$newMemberform->handleRequest($request);

		if ($newMemberform->isSubmitted() && $newMemberform->isValid()) {
			$imageFile = $newMemberform->get('photoFile')->getData();

			if ($imageFile) {
				try {
					$imageFileName = $imageUploader->uploadFile($imageFile);
					$member->setPhotoFile($imageFile);
					$member->setPhotoFilename($imageFileName);
				} catch (FileException $e) {
					$this->addFlash('error', $e->getMessage());

					return $this->redirectToRoute('member_new');
				}
			}

			$entityManager->persist($member);
			$entityManager->flush();

			$this->addFlash('success', 'New member created successfully!');

			return $this->redirectToRoute(
				'member_edit',
				['id' => $member->getId()],
				Response::HTTP_SEE_OTHER
			);
		}

		return $this->render('member/new.html.twig', [
			'member' => $member,
			'personalDataForm' => $newMemberform,
		]);
	}

	/**
	 * Edit existing Member.
	 *
	 * @param Member                 $member        Member entity.
	 * @param Request                $request       HTTP request.
	 * @param EntityManagerInterface $entityManager Entity Manager.
	 *
	 * @return Response HTTP response.
	 */
	#[Route('/member/{id<\d+>}', name: 'member_edit', methods: ['GET', 'POST'])]
	public function edit(
		Member $member,
		Request $request,
		EntityManagerInterface $entityManager,
		ImageUploader $imageUploader,
		MemberSettingsInCookie $memberSettingsInCookie,
	): Response {
		$memberId = $member->getId();
		$isEditingAllowed = $memberSettingsInCookie->getCookieKey($memberId, MemberSettingsInCookie::IS_EDITING_ALLOWED, false);

		// Personal data form.
		$personalDataForm = $this->createForm(MemberPersonalDataFormType::class, $member, ['formAction' => 'edit', 'disabled' => !$isEditingAllowed]);
		$personalDataForm->handleRequest($request);

		if ($personalDataForm->isSubmitted() && $personalDataForm->isValid()) {
			$imageFile = $personalDataForm->get('photoFile')->getData();

			if ($imageFile) {
				try {
					$oldImageFilename = $member->getPhotoFilename();
					$imageFileName = $imageUploader->uploadFile($imageFile);

					$member->setPhotoFile($imageFile);
					$member->setPhotoFilename($imageFileName);

					$imageUploader->deleteFile($oldImageFilename);
				} catch (FileException $e) {
					$this->addFlash('error', $e->getMessage());

					return $this->redirectToRoute('member_new');
				}
			}

			$entityManager->persist($member);
			$entityManager->flush();

			$this->addFlash('success', 'Member updated successfully!');

			return $this->redirectToRoute(
				'member_edit',
				['id' => $memberId],
				Response::HTTP_SEE_OTHER
			);
		}

		// Settings form.
		$settingsForm = $this->createForm(
			MemberSettingsFormType::class,
			$member,
			[MemberSettingsInCookie::IS_EDITING_ALLOWED => $isEditingAllowed],
		);

		$settingsForm->handleRequest($request);

		if ($settingsForm->isSubmitted() && $settingsForm->isValid()) {
			$allowUserEdit = $settingsForm->get('userEdit')->getData();

			$response = $this->redirectToRoute(
				'member_edit',
				[
					'id' => $memberId,
					'tab' => 'settings',
				],
				Response::HTTP_SEE_OTHER
			);

			$memberSettingsInCookie->updateCookie(
				$memberId,
				[MemberSettingsInCookie::IS_EDITING_ALLOWED => $allowUserEdit],
				$response
			);

			$this->addFlash('success', 'Settings saved!');

			return $response;
		}

		return $this->render('member/edit.html.twig', [
			'member' => $member,
			'personalDataForm' => $personalDataForm,
			'settingsForm' => $settingsForm,
		]);
	}

	/**
	 * Deactivate Member.
	 *
	 * @param Member                 $member        Member entity.
	 * @param Request                $request       HTTP request.
	 * @param EntityManagerInterface $entityManager Entity Manager.
	 *
	 * @return Response HTTP response.
	 */
	#[Route(path: '/member/deactivate/{id<\d+>}', name: 'member_deactivate', methods: ['POST'])]
	public function deactivate(Member $member, Request $request, EntityManagerInterface $entityManager): Response
	{
		$token = $request->getPayload()->get('token');

		if (!$this->isCsrfTokenValid('deactivate', $token)) {
			$this->addFlash('error', 'Some security thing failed!');

			return $this->redirectToRoute(
				'member_edit',
				[
					'id' => $member->getId(),
					'tab' => 'settings',
				],
				Response::HTTP_SEE_OTHER
			);
		}

		$member->setIsDeactivated(true);
		$entityManager->persist($member);
		$entityManager->flush();

		$this->addFlash('success', 'Member deactivated successfully!');

		return $this->redirectToRoute(
			'member_edit',
			[
				'id' => $member->getId(),
				'tab' => 'settings',
			],
			Response::HTTP_SEE_OTHER
		);
	}

	/**
	 * Activate Member.
	 *
	 * @param Member                 $member        Member entity.
	 * @param Request                $request       HTTP request.
	 * @param EntityManagerInterface $entityManager Entity Manager.
	 *
	 * @return Response HTTP response.
	 */
	#[Route(path: '/member/activate/{id<\d+>}', name: 'member_activate', methods: ['POST'])]
	public function activate(Member $member, Request $request, EntityManagerInterface $entityManager): Response
	{
		$token = $request->getPayload()->get('token');

		if (!$this->isCsrfTokenValid('activate', $token)) {
			$this->addFlash('error', 'Some security thing failed!');

			return $this->redirectToRoute(
				'member_edit',
				[
					'id' => $member->getId(),
					'tab' => 'settings',
				],
				Response::HTTP_SEE_OTHER
			);
		}

		$member->setIsDeactivated(false);
		$entityManager->persist($member);
		$entityManager->flush();

		$this->addFlash('success', 'Member activated successfully!');

		return $this->redirectToRoute(
			'member_edit',
			[
				'id' => $member->getId(),
				'tab' => 'settings',
			],
			Response::HTTP_SEE_OTHER
		);
	}

	/**
	 * Delete Member.
	 *
	 * @param Member                 $member        Member entity.
	 * @param EntityManagerInterface $entityManager Entity Manager.
	 *
	 * @return Response HTTP response.
	 */
	#[Route(path: '/member/delete/{id<\d+>}', name: 'member_delete', methods: ['POST'])]
	public function delete(Member $member, Request $request, EntityManagerInterface $entityManager): Response
	{
		$token = $request->getPayload()->get('token');

		if (!$this->isCsrfTokenValid('delete', $token)) {
			$this->addFlash('error', 'Some security thing failed!');

			return $this->redirectToRoute(
				'member_edit',
				[
					'id' => $member->getId(),
					'tab' => 'settings',
				],
				Response::HTTP_SEE_OTHER
			);
		}

		$entityManager->remove($member);
		$entityManager->flush();

		$this->addFlash('success', 'Member deleted successfully!');

		return $this->redirectToRoute('member_index', [], Response::HTTP_SEE_OTHER);
	}
}
