<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Assert\AssertionFailedException;

/**
 * Class \SimpleSAML\Assert\Base64Test
 *
 * @package simplesamlphp/assert
 */
#[CoversClass(Assert::class)]
final class Base64Test extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $name
     */
    #[DataProvider('provideBase64')]
    public function testvalidBase64(bool $shouldPass, string $name): void
    {
        try {
            Assert::validBase64($name);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideBase64(): array
    {
        return [
            'empty' => [true, ''],
            'valid' => [true, 'U2ltcGxlU0FNTHBocA=='],
            'invalid padding (1)' => [false, 'U2ltcGxlU0FNTHBocB=='],
            'invalid padding (2)' => [false, 'U2ltcGxlU0FNTHBocAB='],
            'bogus' => [false, '&*$(#&^@!(^%$'],
            'length not dividable by 4' => [false, 'U2ltcGxlU0FTHBocA=='],
        ];
    }
}
