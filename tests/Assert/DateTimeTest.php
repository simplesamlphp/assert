<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Assert\AssertionFailedException;

/**
 * Class \SimpleSAML\Assert\DateTimeTest
 *
 * @package simplesamlphp/assert
 */
#[CoversClass(Assert::class)]
final class DateTimeTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $timestamp
     */
    #[DataProvider('provideDateTime')]
    public function testValidDateTime(bool $shouldPass, string $timestamp): void
    {
        try {
            Assert::validDateTime($timestamp);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @param boolean $shouldPass
     * @param string $timestamp
     */
    #[DataProvider('provideDateTimeZulu')]
    public function testValidDateTimeZulu(bool $shouldPass, string $timestamp): void
    {
        try {
            Assert::validDateTimeZulu($timestamp);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideDateTime(): array
    {
        return [
            'sub-second offset' => [true, '2016-07-27T19:30:00.123+05:00'],
            'sub-second zulu' => [true, '2016-07-27T19:30:00.123Z'],
            'offset' => [true, '2016-07-27T19:30:00+05:00'],
            'zulu' => [true, '2016-07-27T19:30:00Z'],
            'bogus' => [false, '&*$(#&^@!(^%$'],
            'whitespace' => [false, ' '],
        ];
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideDateTimeZulu(): array
    {
        return [
            'sub-second zulu' => [true, '2016-07-27T19:30:00.123Z'],
            'zulu' => [true, '2016-07-27T19:30:00Z'],
            'sub-second offset' => [false, '2016-07-27T19:30:00.123+05:00'],
            'offset' => [false, '2016-07-27T19:30:00+05:00'],
            'bogus' => [false, '&*$(#&^@!(^%$'],
            'whitespace' => [false, ' '],
        ];
    }
}
