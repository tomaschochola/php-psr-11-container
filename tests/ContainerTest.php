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
use Psr\Container\ContainerInterface;
use TomasChochola\Psr\Container\CargoNotFoundException;
use TomasChochola\Psr\Container\DependencyContainer;
use stdClass;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(DependencyContainer::class)]
#[Small]
final class ContainerTest extends TestCase
{
    #[Test]
    public function testCallable(): void
    {
        $container = new DependencyContainer([
            'callable' => static fn(ContainerInterface $container): object => new stdClass(),
        ]);

        $first = $container->get('callable');
        $second = $container->get('callable');

        self::assertNotSame($first, $second);
    }

    #[Test]
    public function testHas(): void
    {
        $container = new DependencyContainer([
            'mixed' => 'mixed',
            'null' => null,
            'string' => 'string',
            'callable' => static fn(ContainerInterface $container): object => new stdClass(),
            'new' => static fn(ContainerInterface $container): object => new stdClass(),
            'locator' => static fn(ContainerInterface $container): object => new stdClass(),
        ]);

        self::assertTrue($container->has('string'));
        self::assertTrue($container->has('null'));
        self::assertTrue($container->has('callable'));
        self::assertTrue($container->has('new'));
        self::assertTrue($container->has('locator'));
        self::assertTrue($container->has('mixed'));
        self::assertFalse($container->has('missing'));
    }

    #[Test]
    public function testLocator(): void
    {
        $container = new DependencyContainer([
            'locator' => static fn(ContainerInterface $container): object => new stdClass(),
        ]);

        $service = $container->get('locator');

        self::assertInstanceOf(stdClass::class, $service);
    }

    #[Test]
    public function testMissing(): void
    {
        $container = new DependencyContainer([]);

        $this->expectException(CargoNotFoundException::class);

        $container->get('missing');
    }

    #[Test]
    public function testMixed(): void
    {
        $container = new DependencyContainer(['null' => null]);

        self::assertNull($container->get('null'));
    }

    #[Test]
    public function testNew(): void
    {
        $container = new DependencyContainer([
            'new' => static fn(ContainerInterface $container): object => new stdClass(),
        ]);

        $service = $container->get('new');

        self::assertInstanceOf(stdClass::class, $service);
    }

    #[Test]
    public function testNull(): void
    {
        $container = new DependencyContainer(['null' => null]);

        self::assertNull($container->get('null'));
    }

    #[Test]
    public function testString(): void
    {
        $container = new DependencyContainer(['string' => 'string']);

        self::assertSame('string', $container->get('string'));
    }
}
