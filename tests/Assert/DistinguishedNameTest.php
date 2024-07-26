<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Assert\AssertionFailedException;

/**
 * Class \SimpleSAML\Assert\DistinguishedNameTest
 *
 * @package simplesamlphp/assert
 */
#[CoversClass(Assert::class)]
final class DistinguishedNameTest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $name
     */
    #[DataProvider('provideDistinguishedName')]
    public function testValidDistinguishedName(bool $shouldPass, string $name): void
    {
        try {
            Assert::validDistinguishedName($name);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<int, array{0: bool, 1: string}>
     */
    public static function provideDistinguishedName(): array
    {
        return [
            [true, 'CN=Dijen\, Tim van,OU=Accounts,DC=simplesamlphp,DC=org'],
            [false, 'Test'],
            [false, '_Test'],
            [false, '_5425e58e-e799-4884-92cc-ca64ecede32f'], // prefixed v4 UUID
            [false, 'Te*st'],
            [false, '1Test'],
            [false, 'Te:st'],
        ];
    }
}
