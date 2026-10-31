<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Container;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

/**
 * @no-named-arguments
 */
class ContainerNotFoundException extends RuntimeException implements NotFoundExceptionInterface
{
}
