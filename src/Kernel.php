<?php

/**
 * Set up all the bundles used by the application.
 */

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Class Kernel.
 */
class Kernel extends BaseKernel
{
	use MicroKernelTrait;
}
