<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Assert\AssertionFailedException;

/**
 * Class \SimpleSAML\Assert\NotInArrayTest
 *
 * @package simplesamlphp/assert
 */
#[CoversClass(Assert::class)]
final class NotInArrayTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param mixed $item
     * @param array<mixed> $arr
     */
    #[DataProvider('provideNotInArray')]
    public function testnotInArray(bool $shouldPass, $item, array $arr): void
    {
        try {
            Assert::notInArray($item, $arr);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<int, array{0: bool, 1: int, 2: array{0: int}}>
     */
    public static function provideNotInArray(): array
    {
        return [
            [true, 0, [1]],
            [false, 1, [1]],
        ];
    }
}
