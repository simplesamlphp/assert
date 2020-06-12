<?php

declare(strict_types=1);

namespace SimpleSAML\Assert;

use BadMethodCallException;
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
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public static function __callStatic($name, $arguments)
    {
        // Handle Exception-parameter
        $last = end($arguments);
        if (is_string($last) && class_exists($last) && is_subclass_of($last, Exception::class)) {
            self::$exceptionClass = $last;

            array_pop($arguments);
        }

        if ('nullOr' === substr($name, 0, 6)) {
            if (null !== $arguments[0]) {
                $method = lcfirst(substr($name, 6));
                call_user_func_array(['static', $method], $arguments);
            }

            return;
        }

        if ('all' === substr($name, 0, 3)) {
            static::isIterable($arguments[0]);

            $method = lcfirst(substr($name, 3));
            $args = $arguments;

            foreach ($arguments[0] as $entry) {
                $args[0] = $entry;

                call_user_func_array(['static', $method], $args);
            }

            return;
        }

        // Handle locally added assertions
        if (method_exists(static::class, $method)) {
            call_user_func_array([static::class, $method], $arguments);
        }

        throw new BadMethodCallException('No such method: ' . $name);
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
