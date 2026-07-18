<?php

/**
 * Plan Controller.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Plan;
use App\Form\PlanDataFormType;
use App\Repository\PlanRepository;
use Doctrine\ORM\EntityManagerInterface;
use ShipMonk\DoctrineEntityPreloader\EntityPreloader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Class PlanController.
 */
#[IsGranted('ROLE_OWNER')]
final class PlanController extends AbstractController
{
	/**
	 * Plans main view.
	 *
	 * @param PlanRepository $repository Plan repository.
	 *
	 * @return Response HTTP response.
	 */
	#[Route(path: '/plans', name: 'plan_index', methods: ['GET'])]
	public function index(PlanRepository $repository): Response
	{
		return $this->render('plan/index.html.twig', [
			'plans' => iterator_to_array($repository->findLatest([
				'perPage' => 20,
				'order' => 'DESC',
			])),
		]);
	}

	/**
	 * New Plan.
	 *
	 * @param Request                $request       HTTP request.
	 * @param EntityManagerInterface $entityManager Entity Manager.
	 *
	 * @return Response HTTP response.
	 */
	#[Route(path: '/plan/new', name: 'plan_new', methods: ['GET', 'POST'])]
	public function new(Request $request, EntityManagerInterface $entityManager): Response
	{
		$plan = new Plan();
		$newPlanForm = $this->createForm(PlanDataFormType::class, $plan, [
			'formAction' => 'new',
			'disabled' => false
		]);
		$newPlanForm->handleRequest($request);

		if ($newPlanForm->isSubmitted() && $newPlanForm->isValid()) {
			$entityManager->persist($plan);
			$entityManager->flush();
			$this->addFlash('success', 'New plan created successfully!');

			return $this->redirectToRoute(
				'plan_new',
				[],
				Response::HTTP_SEE_OTHER
			);
		}

		return $this->render('plan/new.html.twig', [
			'plan' => $plan,
			'planDataForm' => $newPlanForm,
		]);
	}

	/**
	 * Edit existing Plan.
	 *
	 * @param Plan                   $plan          Plan entity.
	 * @param Request                $request       HTTP request.
	 * @param EntityManagerInterface $entityManager Entity Manager.
	 *
	 * @return Response HTTP response.
	 */
	#[Route(path: '/plan/{id<\d+>}', name: 'plan_edit', methods: ['GET', 'POST'])]
	public function edit(
		Plan $plan,
		Request $request,
		EntityManagerInterface $entityManager,
		EntityPreloader $entityPreloader,
	): Response {
		$planId = $plan->getId();

		// Plan data form.
		$planDataForm = $this->createForm(PlanDataFormType::class, $plan, [
			'formAction' => 'edit',
			'disabled' => true
		]);
		$planDataForm->handleRequest($request);

		if ($planDataForm->isSubmitted() && $planDataForm->isValid()) {
			$entityManager->persist($plan);
			$entityManager->flush();

			$this->addFlash('success', 'Plan updated successfully!');

			return $this->redirectToRoute(
				'plan_edit',
				['id' => $planId],
				Response::HTTP_SEE_OTHER
			);
		}

		$memberships = $entityPreloader->preload([$plan], 'memberships');
		$members = $entityPreloader->preload($memberships, 'memberSubscriber');

		return $this->render('plan/edit.html.twig', [
			'plan' => $plan,
			'members' => $members,
			'planDataForm' => $planDataForm,
		]);
	}
}
