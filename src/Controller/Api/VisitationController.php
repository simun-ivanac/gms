<?php

/**
 * Visitation Controller.
 */

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Member;
use App\Entity\TeamMember;
use App\Repository\VisitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class DashboardController.
 */
final class VisitationController extends AbstractController
{
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
