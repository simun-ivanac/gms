<?php

/**
 * Team Member Controller.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TeamMember;
use App\Form\TeamMemberPersonalDataFormType;
use App\Form\TeamMemberPasswordFormType;
use App\Repository\TeamMemberRepository;
use App\Service\ImageUploader;
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
		$newTeamMemberForm = $this->createForm(TeamMemberPersonalDataFormType::class, $teamMember);
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

			return $this->redirectToRoute('team_member_index');
		}

		return $this->render('team_member/new.html.twig', [
			'teamMember' => $teamMember,
			'personalDataForm' => $newTeamMemberForm,
		]);
	}
}
