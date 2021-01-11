<?php

declare(strict_types=1);

namespace SAML2\Assert;

use BadMethodCallException;
use LogicException;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Assert\AssertionFailedException;

/**
 * Class \SimpleSAML\Assert\Assert
 *
 * @package simplesamlphp/saml2
 */
final class AssertTest extends TestCase
{
    /**
     */
    public function testAssertionPassing(): void
    {
        $result = Assert::integer(1);
        $this->assertNull($result);
    }

    /**
     */
    public function testAssertionFailingThrowsException(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::nullOrSame('a', 'b');
    }


    /**
     */
    public function testAssertionFailingWithCustomExceptionThrowsCustomException(): void
    {
        $this->expectException(LogicException::class);
        Assert::allSame(['a', 'b', 'c'], 'b', LogicException::class);
    }


    /**
     */
    public function testUnknownAssertionRaisesBadMethodCallException(): void
    {
        $this->expectException(BadMethodCallException::class);
        Assert::thisAssertionDoesNotExist('a', 'b', LogicException::class);
    }
}
