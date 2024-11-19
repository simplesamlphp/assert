<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Assert\AssertionFailedException;

/**
 * Class \SimpleSAML\Assert\QNameTest
 *
 * @package simplesamlphp/assert
 */
#[CoversClass(Assert::class)]
final class QNameTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $name
     */
    #[DataProvider('provideQName')]
    public function testValidQName(bool $shouldPass, string $name): void
    {
        try {
            Assert::validQName($name);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<int, array{0: bool, 1: string}>
     */
    public static function provideQName(): array
    {
        return [
            [true, 'some:Test'],
            [true, 'some:_Test'],
            [true, '_some:_Test'],
            [true, 'Test'],
            [false, '1Test'],
            [false, 'Te*st'],
            // Trailing newlines are forbidden
            [false, "some:Test\n"],
        ];
    }
}
