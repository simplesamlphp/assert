<?php

declare(strict_types=1);

namespace SAML2\Assert;

use LogicException;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Assert\AssertionFailedException;

/**
 * Class \SimpleSAML\Assert\Assert
 *
 * @author Tim van Dijen, <tvdijen@gmail.com>
 * @package simplesamlphp/saml2
 */
final class AssertTest extends TestCase
{
    /**
     * @return void
     */
    public function testAssertionFailingThrowsException(): void
    {
        $this->expectException(AssertionFailedException::class);
        Assert::same('a', 'b');
    }


    /**
     * @return void
     */
    public function testAssertionFailingWithCustomExceptionThrowsCustomException(): void
    {
        $this->expectException(LogicException::class);
        Assert::same('a', 'b', LogicException::class);
    }
}
