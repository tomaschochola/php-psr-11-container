<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Container;

use Psr\Container\ContainerInterface;

/**
 * @no-named-arguments
 */
interface ResolverInterface
{
    public function resolve(ContainerInterface $container): mixed;
}
