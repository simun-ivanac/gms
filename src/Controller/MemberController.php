<?php

/**
 * Member Controller.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Member;
use App\Form\MemberFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class MemberController.
 */
#[Route('/member')]
final class MemberController extends AbstractController
{
	#[Route('/new', name: 'member_new')]
	public function new(Request $request, EntityManagerInterface $entityManager): Response
	{
		$member = new Member();
		$form = $this->createForm(MemberFormType::class, $member);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($member);
			$entityManager->flush();
			return $this->redirectToRoute('dashboard_index');
		}

		return $this->render('member/new.html.twig', [
			'memberForm' => $form,
		]);
	}
}
