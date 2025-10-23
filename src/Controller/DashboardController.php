<?php

/**
 * Dashboard Controller.
 */

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class DashboardController.
 */
#[Route('/dashboard')]
final class DashboardController extends AbstractController
{
	/**
	 * Handle dashboard main view logic.
	 */
	#[Route('/', name: 'dashboard_index')]
	public function index(): Response
	{
		return $this->render('dashboard/index.html.twig');
	}
}
