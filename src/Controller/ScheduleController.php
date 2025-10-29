<?php

/**
 * Schedule Controller.
 */

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class ScheduleController.
 */
final class ScheduleController extends AbstractController
{
	/**
	 * Schedule main view.
	 *
	 * @return Response HTTP response
	 */
	#[Route('/schedule', name: 'schedule_index')]
	public function index(): Response
	{
		return $this->render('schedule/index.html.twig');
	}
}
