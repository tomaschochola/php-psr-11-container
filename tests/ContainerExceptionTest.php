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

namespace Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use Psr\Container\NotFoundExceptionInterface;
use TomasChochola\Psr\Container\ContainerNotFoundException;

use function class_implements;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(ContainerNotFoundException::class)]
#[Small]
final class ContainerExceptionTest extends TestCase
{
    #[Test]
    public function test(): void
    {
        $exception = new ContainerNotFoundException('missing');
        $implements = class_implements(ContainerNotFoundException::class);

        self::assertIsIterable($implements);
        self::assertContains(NotFoundExceptionInterface::class, $implements);
        self::assertSame('missing', $exception->getMessage());
    }
}
