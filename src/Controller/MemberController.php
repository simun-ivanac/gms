<?php

/**
 * Member Controller.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberFormType;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
			'members' => $repository->findAll(),
		]);
	}

	/**
	 * New Member.
	 *
	 * @param Request                $request       HTTP request.
	 * @param EntityManagerInterface $entityManager Entity Manager.
	 *
	 * @return Response HTTP response.
	 */
	#[Route('/member/new', name: 'member_new')]
	public function new(Request $request, EntityManagerInterface $entityManager): Response
	{
		$member = new Member();
		$member->setIsActive(false);

		$form = $this->createForm(MemberFormType::class, $member);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($member);
			$entityManager->flush();

			$this->addFlash('success', 'New member is created successfully!');

			return $this->redirectToRoute('member_index', [], Response::HTTP_SEE_OTHER);
		}

		return $this->render('member/new.html.twig', [
			'memberForm' => $form,
		]);
	}
}
