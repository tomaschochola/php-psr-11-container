<?php

declare(strict_types=1);

namespace TomasChochola\Psr\Container;

use Override;
use Psr\Container\ContainerInterface;

/**
 * @no-named-arguments
 */
readonly class NewResolver implements ResolverInterface
{
    private string $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    #[Override()]
    public function resolve(ContainerInterface $container): mixed
    {
        return new $this->class();
    }
}
