<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Assert;

use BadMethodCallException;
use DateTimeImmutable;
use ArrayIterator;
use LogicException;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Assert\AssertionFailedException;
use SimpleSAML\Test\Utils\TestClass;
use StdClass;

use function getcwd;
use function opendir;

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
    public function testNullOrCustomAssertionWorks(): void
    {
        Assert::nullOrStringPlausibleBase64('U2ltcGxlU0FNTHBocA==', AssertionFailedException::class);
        Assert::nullOrStringPlausibleBase64(null, AssertionFailedException::class);

        // Also make sure it keeps working for Webmozart's native assertions
        Assert::nullOrString(null);
        Assert::nullOrString('test');
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
     * @doesNotPerformAssertions
     */
    public function testValidURN(): void
    {
        Assert::validURN('urn:x-simplesamlphp:phpunit', AssertionFailedException::class);
    }


    /**
     * @doesNotPerformAssertions
     */
    public function testValidURL(): void
    {
        Assert::validURL('https://www.simplesamlphp.org', AssertionFailedException::class);
    }


    /**
     * @doesNotPerformAssertions
     */
    public function testValidURIwithURL(): void
    {
        Assert::validURI('https://www.simplesamlphp.org', AssertionFailedException::class);
    }


    /**
     * @doesNotPerformAssertions
     */
    public function testValidURIwithURIReference(): void
    {
        Assert::validURI('#_53d830ab1be17291a546c95c7f1cdf8d3d23c959e6', AssertionFailedException::class);
    }


    /**
     * @doesNotPerformAssertions
     */
    public function testValidURIwithURN(): void
    {
        Assert::validURI('urn:x-simplesamlphp:phpunit', AssertionFailedException::class);
    }


    /**
     */
    public function testInvalidURN(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::validURN('stupid value', AssertionFailedException::class);
    }


    /**
     */
    public function testInvalidURL(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::validURL('stupid value', AssertionFailedException::class);
    }


    /**
     */
    public function testInvalidURI(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::validURI('stupid value', AssertionFailedException::class);
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
     * @doesNotPerformAssertions
     */
    public function testValidSubSecondDateTime(): void
    {
        Assert::validDateTime('2016-07-27T19:30:00.123+05:00', AssertionFailedException::class);
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
    public function testValidSubSecondDateTimeNotZulu(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::validDateTimeZulu('2016-07-27T19:30:00.123+05:00', AssertionFailedException::class);
    }


    /**
     * @doesNotPerformAssertions
     */
    public function testValidSubSecondDateTimeZulu(): void
    {
        Assert::validDateTimeZulu('2016-07-27T19:30:00.123Z', AssertionFailedException::class);
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


    /**
     */
    public function testInvalidNCName(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::validNCName('te:st');
    }


    /**
     * @doesNotPerformAssertions
     */
    public function testValidNCName(): void
    {
        Assert::validNCName('test');
    }


    /**
     */
    public function testInvalidQName(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::validQName('1test');
    }


    /**
     * @doesNotPerformAssertions
     */
    public function testValidQName(): void
    {
        Assert::validQName('a:test');
    }


    /**
     * @dataProvider provideValue
     * @param mixed $value
     * @param string $expected
     */
    public function testValueToString($value, string $expected): void
    {
        $assert = new Assert();
        $method = new ReflectionMethod(Assert::class, 'valueToString');
        $method->setAccessible(true);

        $this->assertEquals($expected, $method->invoke($assert, $value));
    }


    /**
     * @return array
     */
    public function provideValue(): array
    {
        $stringable = new TestClass('phpunit');

        $dateTime = new DateTimeImmutable('2000-01-01T00:00:00+00:00');

        $otherObject = new StdClass();

        $resource = opendir(getcwd());

        return [
            'null' => [null, 'null'],
            'true' => [true, 'true'],
            'false' => [false, 'false'],
            'array' => [[], 'array'],
            'Stringable' => [$stringable, 'SimpleSAML\Test\Utils\TestClass: "phpunit"'],
            'DateTime' => [$dateTime, 'DateTimeImmutable: "2000-01-01T00:00:00+00:00"'],
            'object' => [$otherObject, 'stdClass'],
            'resource' => [$resource, 'resource'],
            'string' => ['string', '"string"'],
            'other' => [1, '1'],
        ];
    }
}
