<?php

/**
 * Visitation Controller.
 */

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Member;
use App\Entity\TeamMember;
use App\Entity\Visitation;
use App\Repository\MemberRepository;
use App\Repository\TeamMemberRepository;
use App\Repository\VisitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class DashboardController.
 */
final class VisitationController extends AbstractController
{
	/**
	 * API token.
	 */
	private const TEMP_VISITATION_TOKEN = 'Nud5jk7TFi!grHu';

	/**
	 * API main response.
	 *
	 * @param VisitationRepository $repository Repository.
	 * @param SerializerInterface  $serializer Serializer.
	 *
	 * @return JsonResponse
	 */
	#[Route(path: '/api/visitations', name: 'visitation_api')]
	public function index(VisitationRepository $repository, SerializerInterface $serializer): JsonResponse
	{
		$results['visitations'] = $repository->findAllEntries();

		$jsonContent = $serializer->serialize($results, 'json');

		return JsonResponse::fromJsonString($jsonContent);
	}

	/**
	 * Create new visitation.
	 *
	 * @param Request                $request              Request.
	 * @param EntityManagerInterface $entityManager        Entity Manager.
	 * @param MemberRepository       $memberRepository     Member repository.
	 * @param TeamMemberRepository   $teamMemberRepository Team member repository.
	 * @param SerializerInterface    $serializer           Serializer.
	 *
	 * @return JsonResponse
	 */
	#[Route(path: '/api/visitations/new', name: 'visitation_api_new', methods: ['POST'])]
	public function new(
		Request $request,
		EntityManagerInterface $entityManager,
		MemberRepository $memberRepository,
		TeamMemberRepository $teamMemberRepository,
		SerializerInterface $serializer
	): JsonResponse {
		$token = $request->getPayload()->get('token');
		$userType = $request->getPayload()->get('userType');
		$userId = $request->getPayload()->get('userId');
		$toCreate = true;

		// Check token.
		if ($token !== self::TEMP_VISITATION_TOKEN) {
			$toCreate = false;
			$statusCode = JsonResponse::HTTP_FORBIDDEN;
			$results['message'] = 'Access denied. You are not allowed to proceed.';
		}

		// Check user type.
		if ($userType !== 'member' && $userType !== 'teamMember') {
			$toCreate = false;
			$statusCode = JsonResponse::HTTP_BAD_REQUEST;
			$results['message'] = 'User type field is invalid.';
		}

		// Check if user ID exists.
		if (!$userId) {
			$toCreate = false;
			$statusCode = JsonResponse::HTTP_BAD_REQUEST;
			$results['message'] = 'User ID field cannot be empty.';
		}

		// Check if user exists.
		$findUser = $userType === 'member' ? $memberRepository->find($userId) : $teamMemberRepository->find($userId);

		if (!$findUser) {
			$toCreate = false;
			$statusCode = JsonResponse::HTTP_BAD_REQUEST;
			$results['message'] = 'User ID field is invalid.';
		}

		// Send error?
		if (!$toCreate) {
			$results['status'] = 'error';
			$jsonContent = $serializer->serialize($results, 'json');
			return JsonResponse::fromJsonString($jsonContent, $statusCode);
		}

		// Create entry.
		$visitation = new Visitation();
		$visitation->setStatus('success');
		$visitation->setMessage('User entered the gym.');
		$userType === 'member' ? $visitation->setMember($findUser) : $visitation->setTeamMember($findUser);
		$entityManager->persist($visitation);
		$entityManager->flush();

		$results = [
			'id' => $visitation->getId(),
			'timestamp' => $visitation->getTimestamp(),
			'status' => 'success',
			'message' => 'User entered the gym.',
		];

		if ($userType === 'member') {
			$results['member'] = (int) $userId;
		} else {
			$results['teamMember'] = (int) $userId;
		}

		$jsonContent = $serializer->serialize($results, 'json');

		return JsonResponse::fromJsonString($jsonContent);
	}

	/**
	 * Get visitations for a team member.
	 *
	 * @param VisitationRepository $repository Repository.
	 * @param TeamMember           $teamMember Team member.
	 * @param SerializerInterface  $serializer Serializer.
	 *
	 * @return JsonResponse
	 */
	#[Route(path: '/api/visitations/team-member/{id<\d+>}', name: 'visitation_api_for_team_member')]
	public function getVisitationsForTeamMember(
		VisitationRepository $repository,
		TeamMember $teamMember,
		SerializerInterface $serializer
	): JsonResponse {
		$results['visitations'] = $repository->findByTeamMember($teamMember->getId());

		$jsonContent = $serializer->serialize($results, 'json');

		return JsonResponse::fromJsonString($jsonContent);
	}

	/**
	 * Get visitations for a member.
	 *
	 * @param VisitationRepository $repository Repository.
	 * @param Member               $member     Member.
	 * @param SerializerInterface  $serializer Serializer.
	 *
	 * @return JsonResponse
	 */
	#[Route(path: '/api/visitations/member/{id<\d+>}', name: 'visitation_api_for_member')]
	public function getVisitationsForMember(
		VisitationRepository $repository,
		Member $member,
		SerializerInterface $serializer
	): JsonResponse {
		$results['visitations'] = $repository->findByMember($member->getId());

		$jsonContent = $serializer->serialize($results, 'json');

		return JsonResponse::fromJsonString($jsonContent);
	}
}
