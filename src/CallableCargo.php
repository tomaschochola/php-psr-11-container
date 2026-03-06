<?php

/**
 * @author Tomáš Chochola <tomaschochola@tomaschochola.cz>
 * @copyright © 2026 Tomáš Chochola <tomaschochola@tomaschochola.cz>
 *
 * @license CC-BY-ND-4.0
 *
 * @see {@link https://creativecommons.org/licenses/by-nd/4.0/} License
 * @see {@link https://github.com/tomaschochola} GitHub Profile
 * @see {@link https://github.com/sponsors/tomaschochola} GitHub Sponsors
 */

declare(strict_types=1);

namespace TomasChochola\Psr\Container;

use Override;
use Psr\Container\ContainerInterface;

/**
 * @no-named-arguments
 */
readonly class CallableCargo implements CargoInterface
{
    /**
     * @var callable(ContainerInterface): mixed
     */
    protected readonly mixed $callable;

    /**
     * @param callable(ContainerInterface): mixed $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    #[Override]
    public function open(ContainerInterface $container): mixed
    {
        return ($this->callable)($container);
    }
}
