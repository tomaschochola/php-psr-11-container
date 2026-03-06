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
readonly class MixedCargo implements CargoInterface
{
    protected readonly mixed $mixed;

    public function __construct(mixed $mixed)
    {
        $this->mixed = $mixed;
    }

    #[Override]
    public function open(ContainerInterface $container): mixed
    {
        return $this->mixed;
    }
}
