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
use TomasChochola\Psr\Container\Container;
use TomasChochola\Psr\Container\ContainerNotFoundException;
use TomasChochola\Psr\Container\FactoryResolver;
use TomasChochola\Psr\Container\LocatorResolver;
use TomasChochola\Psr\Container\NewResolver;
use TomasChochola\Psr\Container\SingletonResolver;

/**
 * @internal
 *
 * @no-named-arguments
 */
#[CoversClass(Container::class)]
#[CoversClass(ContainerNotFoundException::class)]
#[CoversClass(FactoryResolver::class)]
#[CoversClass(LocatorResolver::class)]
#[CoversClass(NewResolver::class)]
#[CoversClass(SingletonResolver::class)]
#[Small()]
final class ContainerTest extends TestCase
{
    #[Test()]
    public function constructorResolversUseTheirDeclaredConstructionStrategy(): void
    {
        $container = new Container([
            'located' => new LocatorResolver(LocatedDependency::class),
            'new' => new NewResolver(NewDependency::class),
        ]);

        $located = $container->get('located');
        $new = $container->get('new');

        self::assertInstanceOf(LocatedDependency::class, $located);
        self::assertSame($container, $located->container);
        self::assertInstanceOf(NewDependency::class, $new);
    }

    #[Test()]
    public function factoryResolverCreatesAValueForEveryLookup(): void
    {
        $invocations = 0;

        $container = new Container([
            'factory' => new FactoryResolver(static function (ContainerInterface $resolvedContainer) use (&$invocations): FactoryDependency {
                ++$invocations;

                return new FactoryDependency($resolvedContainer);
            }),
        ]);

        $first = $container->get('factory');
        $second = $container->get('factory');

        self::assertInstanceOf(FactoryDependency::class, $first);
        self::assertInstanceOf(FactoryDependency::class, $second);
        self::assertNotSame($first, $second);
        self::assertSame($container, $first->container);
        self::assertSame($container, $second->container);
        self::assertSame(2, $invocations);
    }

    #[Test()]
    public function missingEntryThrowsPsrNotFoundException(): void
    {
        $container = new Container([]);

        $this->expectException(ContainerNotFoundException::class);
        $this->expectExceptionMessageIs('missing');

        (void) $container->get('missing');
    }

    #[Test()]
    public function rawEntriesPreserveValuesIncludingNull(): void
    {
        $value = new NewDependency();

        $container = new Container([
            'null' => null,
            'value' => $value,
        ]);

        self::assertTrue($container->has('null'));
        self::assertNull($container->get('null'));
        self::assertTrue($container->has('value'));
        self::assertSame($value, $container->get('value'));
        self::assertFalse($container->has('missing'));
    }

    #[Test()]
    public function singletonResolverCachesNullAsAResolvedValue(): void
    {
        $invocations = 0;

        $container = new Container([
            'singleton' => new SingletonResolver(static function (ContainerInterface $resolvedContainer) use (&$invocations): null {
                ++$invocations;

                return null;
            }),
        ]);

        self::assertNull($container->get('singleton'));
        self::assertNull($container->get('singleton'));
        self::assertSame(1, $invocations);
    }
}

/**
 * @internal
 *
 * @no-named-arguments
 */
final readonly class FactoryDependency
{
    public function __construct(public ContainerInterface $container)
    {
    }
}

/**
 * @internal
 *
 * @no-named-arguments
 */
final readonly class LocatedDependency
{
    public function __construct(public ContainerInterface $container)
    {
    }
}

/**
 * @internal
 *
 * @no-named-arguments
 */
final class NewDependency
{
}
