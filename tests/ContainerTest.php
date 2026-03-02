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
use TomasChochola\Psr\Container\CallableResolver;
use TomasChochola\Psr\Container\Container;
use TomasChochola\Psr\Container\ContainerException;
use TomasChochola\Psr\Container\LocatorResolver;
use TomasChochola\Psr\Container\MixedResolver;
use TomasChochola\Psr\Container\NewResolver;
use stdClass;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(Container::class)]
#[Small]
final class ContainerTest extends TestCase
{
    #[Test]
    public function testCallable(): void
    {
        $container = new Container([
            'callable' => new CallableResolver(static fn(ContainerInterface $container): object => new stdClass()),
        ]);

        $first = $container->get('callable');
        $second = $container->get('callable');

        self::assertNotSame($first, $second);
    }

    #[Test]
    public function testHas(): void
    {
        $container = new Container([
            'mixed' => new MixedResolver('mixed'),
            'null' => null,
            'string' => 'string',
            'callable' => new CallableResolver(static fn(ContainerInterface $container): object => new stdClass()),
            'new' => new NewResolver(stdClass::class),
            'locator' => new LocatorResolver(stdClass::class),
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
        $container = new Container([
            'locator' => new LocatorResolver(stdClass::class),
        ]);

        $service = $container->get('locator');

        self::assertInstanceOf(stdClass::class, $service);
    }

    #[Test]
    public function testMissing(): void
    {
        $container = new Container([]);

        $this->expectException(ContainerException::class);

        $container->get('missing');
    }

    #[Test]
    public function testMixed(): void
    {
        $container = new Container(['null' => new MixedResolver(null)]);

        self::assertNull($container->get('null'));
    }

    #[Test]
    public function testNew(): void
    {
        $container = new Container([
            'new' => new NewResolver(stdClass::class),
        ]);

        $service = $container->get('new');

        self::assertInstanceOf(stdClass::class, $service);
    }

    #[Test]
    public function testNull(): void
    {
        $container = new Container(['null' => null]);

        self::assertNull($container->get('null'));
    }

    #[Test]
    public function testString(): void
    {
        $container = new Container(['string' => 'string']);

        self::assertSame('string', $container->get('string'));
    }
}
