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
readonly class NewCargo implements CargoInterface
{
    /**
     * @var class-string
     */
    protected readonly string $class;

    /**
     * @param class-string $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }

    #[Override]
    public function open(ContainerInterface $container): mixed
    {
        return new $this->class();
    }
}
