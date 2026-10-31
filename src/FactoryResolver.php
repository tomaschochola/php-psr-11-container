<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Container;

use Override;
use Psr\Container\ContainerInterface;

/**
 * @no-named-arguments
 */
readonly class FactoryResolver implements ResolverInterface
{
    /**
     * @var callable(ContainerInterface): mixed
     */
    private mixed $factory;

    /**
     * @param callable(ContainerInterface): mixed $factory
     */
    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    #[Override()]
    public function resolve(ContainerInterface $container): mixed
    {
        return ($this->factory)($container);
    }
}
