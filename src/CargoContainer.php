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

use function array_key_exists;

/**
 * @no-named-arguments
 */
readonly class CargoContainer implements ContainerInterface
{
    /**
     * @var array<int|string, mixed>
     */
    protected readonly array $registry;

    /**
     * @param array<int|string, mixed> $registry
     */
    public function __construct(array $registry)
    {
        $this->registry = $registry;
    }

    #[Override]
    public function get(string $id): mixed
    {
        $found = $this->registry[$id] ?? null;

        if ($found instanceof CargoInterface) {
            return $found->open($this);
        }

        if ($found !== null || array_key_exists($id, $this->registry)) {
            return $found;
        }

        throw new CargoNotFoundException($id);
    }

    #[Override]
    public function has(string $id): bool
    {
        return isset($this->registry[$id]) || array_key_exists($id, $this->registry);
    }
}
