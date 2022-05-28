<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Assert;

use BadMethodCallException;
use DateTime;
use ArrayIterator;
use LogicException;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Assert\AssertionFailedException;
use StdClass;

/**
 * Class \SimpleSAML\Assert\Assert
 *
 * @package simplesamlphp/saml2
 */
final class AssertTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testAssertionPassing(): void
    {
        /** @psalm-suppress TooFewArguments */
        Assert::integer(1);
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
     * @doesNotPerformAssertions
     */
    public function testValidBase64(): void
    {
        Assert::stringPlausibleBase64('U2ltcGxlU0FNTHBocA==', AssertionFailedException::class);
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
     * @doesNotPerformAssertions
     */
    public function testValidDateTime(): void
    {
        Assert::validDateTime('2016-07-27T19:30:00+05:00', AssertionFailedException::class);
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
        $this->expectException(AssertionFailedException::class);
        Assert::validDateTimeZulu('2016-07-27T19:30:00+05:00', AssertionFailedException::class);
    }


    /**
     * @doesNotPerformAssertions
     */
    public function testValidDateTimeZulu(): void
    {
        Assert::validDateTimeZulu('2016-07-27T19:30:00Z', AssertionFailedException::class);
    }


    /**
     */
    public function testNotInArrayIfInArray(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::notInArray(1, [1]);
    }


    /**
     * @doesNotPerformAssertions
     */
    public function testNotInArrayIfNotInArray(): void
    {
        Assert::notInArray(0, [1]);
    }
}
