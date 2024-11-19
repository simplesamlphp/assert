<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Assert\AssertionFailedException;

/**
 * Class \SimpleSAML\Assert\NMTokenTest
 *
 * @package simplesamlphp/assert
 */
#[CoversClass(Assert::class)]
final class NMTokenTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $nmtoken
     */
    #[DataProvider('provideNMToken')]
    public function testValidToken(bool $shouldPass, string $nmtoken): void
    {
        try {
            Assert::validNMToken($nmtoken);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<int, array{0: bool, 1: string}>
     */
    public static function provideNMToken(): array
    {
        return [
            [true, 'Snoopy'],
            [true, 'CMS'],
            [true, 'fööbár'],
            [true, '1950-10-04'],
            [true, '0836217462'],
            // Spaces are forbidden
            [false, 'foo bar'],
            // Commas are forbidden
            [false, 'foo,bar'],
            // Trailing newlines are forbidden
            [false, "foobar\n"],
        ];
    }
}
