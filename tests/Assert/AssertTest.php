<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Assert;

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


    /**
     */
    public function testValidBase64(): void
    {
        $result = Assert::stringPlausibleBase64('U2ltcGxlU0FNTHBocA==', AssertionFailedException::class);
        $this->assertNull($result);
    }


    /**
     */
    public function testInvalidBase64(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::stringPlausibleBase64('&*$(#&^@!(^%$', AssertionFailedException::class);
    }


    /**
     */
    public function testInvalidDateTime(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::validDateTime('&*$(#&^@!(^%$', AssertionFailedException::class);
    }


    /**
     */
    public function testValidDateTime(): void
    {
        $result = Assert::validDateTime('2016-07-27T19:30:00+05:00', AssertionFailedException::class);
        $this->assertNull($result);
    }


    /**
     */
    public function testInvalidDateTimeZulu(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::validDateTimeZulu('&*$(#&^@!(^%$', AssertionFailedException::class);
    }


    /**
     */
    public function testValidDateTimeNotZulu(): void
    {
        $result = Assert::validDateTimeZulu('2016-07-27T19:30:00+05:00', AssertionFailedException::class);
        $this->assertNull($result);
    }


    /**
     */
    public function testValidDateTimeZulu(): void
    {
        $result = Assert::validDateTimeZulu('2016-07-27T19:30:00Z', AssertionFailedException::class);
        $this->assertNull($result);
    }
}
