<?php

declare(strict_types=1);

namespace SimpleSAML\Assert;

use Webmozart\Assert as Webmozart;

/**
 * Webmozart\Assert wrapper class
 *
 * @author Tim van Dijen, <tvdijen@gmail.com>
 * @package simplesamlphp/assert
 */
final class Assert extends Webmozart\Assert
{
    /**
     * Exception to throw when an assertion failed.
     *
     * @var string
     */
    protected $exceptionClass = AssertionFailedException::class;


    /**
     * @param string $method
     * @param array $arguments
     * @return void
     */
    public static function __callStatic($method, $arguments)
    {
        call_user_func_array([parent::class, $method], $arguments);
    }


    /**
     * @param string $message
     *
     * @throws \SimpleSAML\Assert\AssertionFailedException
     *
     * @psalm-pure this method is not supposed to perform side-effects
     * @psalm-suppress InvalidStringClass
     */
    protected static function reportInvalidArgument($message): void
    {
        throw new static::$exceptionClass($message);
    }
}
