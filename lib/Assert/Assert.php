<?php

declare(strict_types=1);

namespace SimpleSAML\Assert;

use Exception;
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
     * @var class-string
     */
    protected static $exceptionClass = AssertionFailedException::class;


    /**
     * @param string $method
     * @param array $arguments
     * @return void
     */
    public static function __callStatic($method, $arguments)
    {
        // Handle Exception-parameter
        $last = end($arguments);
        if (is_string($last) && class_exists($last) && is_subclass_of($last, Exception::class)) {
            self::$exceptionClass = $last;

            array_pop($arguments);
        }

        // Handle locally added assertions
        if (method_exists(static::class, $method)) {
            call_user_func_array([static::class, $method], $arguments);
        } else {
            call_user_func_array([parent::class, $method], $arguments);
        }
    }


    /**
     * @param string $message
     * @return void
     *
     * @throws \SimpleSAML\Assert\AssertionFailedException
     *
     * @psalm-pure this method is not supposed to perform side-effects
     * @psalm-suppress InvalidStringClass
     */
    protected static function reportInvalidArgument($message)
    {
        throw new self::$exceptionClass($message);
    }
}
