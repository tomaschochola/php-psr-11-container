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

use ArrayObject;
use NoDiscard;
use Override;
use Psr\Container\ContainerInterface;

use function array_key_exists;
use function is_array;
use function is_callable;

/**
 * @no-named-arguments
 */
readonly class Container implements ContainerInterface
{
    /**
     * @var array<mixed, mixed>
     */
    private readonly array $registry;

    /**
     * @var ArrayObject<string, mixed>
     */
    private readonly ArrayObject $cache;

    /**
     * @param array<mixed, mixed> $registry
     */
    public function __construct(array $registry)
    {
        $this->registry = $registry;
        $this->cache = new ArrayObject();
    }

    #[NoDiscard]
    #[Override]
    public function get(string $id): mixed
    {
        if ($this->cache->offsetExists($id)) {
            return $this->cache->offsetGet($id);
        }

        $found = $this->registry[$id] ?? null;

        if (is_callable($found)) {
            $resolved = $found($this);

            if (is_array($found)) {
                $this->cache->offsetSet($id, $resolved);
            }

            return $resolved;
        }

        if ($found !== null || array_key_exists($id, $this->registry)) {
            return $found;
        }

        throw new ContainerNotFoundException($id);
    }

    #[NoDiscard]
    #[Override]
    public function has(string $id): bool
    {
        return $this->cache->offsetExists($id) || isset($this->registry[$id]) || array_key_exists($id, $this->registry);
    }
}
