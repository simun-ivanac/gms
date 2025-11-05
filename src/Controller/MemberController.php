<?php

/**
 * Member Controller.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberFormType;
use App\Repository\MemberRepository;
use App\Service\ImageUploader;
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
	#[Route('/members', name: 'member_index')]
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
	#[Route('/member/new', name: 'member_new')]
	public function new(Request $request, EntityManagerInterface $entityManager, ImageUploader $imageUploader): Response
	{
		$member = new Member();
		$form = $this->createForm(MemberFormType::class, $member);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$imageFile = $form->get('photoFile')->getData();

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

			$this->addFlash('success', 'New member is created successfully!');

			return $this->redirectToRoute('member_index', [], Response::HTTP_SEE_OTHER);
		}

		return $this->render('member/new.html.twig', [
			'memberForm' => $form,
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
	#[Route('/member/{id<\d+>}', name: 'member_edit')]
	public function edit(Member $member, Request $request, EntityManagerInterface $entityManager, ImageUploader $imageUploader): Response
	{
		$form = $this->createForm(MemberFormType::class, $member);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$imageFile = $form->get('photoFile')->getData();

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

			return $this->redirectToRoute('member_edit', [
				'id' => $member->getId(),
				'httpResponse' => Response::HTTP_SEE_OTHER,
			]);
		}

		return $this->render('member/edit.html.twig', [
			'memberForm' => $form,
			'member' => $member,
			'isEditingDisabled' => false,
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

			return $this->redirectToRoute('member_edit', ['id' => $member->getId()], Response::HTTP_SEE_OTHER);
		}

		$member->setIsDeactivated(true);
		$entityManager->persist($member);
		$entityManager->flush();

		$this->addFlash('success', 'Member deactivated successfully!');

		return $this->redirectToRoute('member_index', [], Response::HTTP_SEE_OTHER);
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

			return $this->redirectToRoute('member_edit', ['id' => $member->getId()], Response::HTTP_SEE_OTHER);
		}

		$member->setIsDeactivated(false);
		$entityManager->persist($member);
		$entityManager->flush();

		$this->addFlash('success', 'Member activated successfully!');

		return $this->redirectToRoute('member_index', [], Response::HTTP_SEE_OTHER);
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
			$this->addFlash('error', 'Some security thing is not good!');

			return $this->redirectToRoute('member_edit', ['id' => $member->getId()], Response::HTTP_SEE_OTHER);
		}

		$entityManager->remove($member);
		$entityManager->flush();

		$this->addFlash('success', 'Member deleted successfully!');

		return $this->redirectToRoute('member_index', [], Response::HTTP_SEE_OTHER);
	}
}
