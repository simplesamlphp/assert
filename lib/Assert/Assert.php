<?php

declare(strict_types=1);

namespace SimpleSAML\Assert;

use BadMethodCallException;
use Exception;
use Webmozart\Assert\Assert as Webmozart;

/**
 * Webmozart\Assert wrapper class
 *
 * @author Tim van Dijen, <tvdijen@gmail.com>
 * @package simplesamlphp/assert
 */
final class Assert 
{
    /**
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public static function __callStatic($name, $arguments)
    {
        // Handle Exception-parameter
        $exception = \InvalidArgumentException::class;
        $last = end($arguments);
        if (is_string($last) && class_exists($last) && is_subclass_of($last, \Throwable::class)) {
            $exception = $last;

            array_pop($arguments);
        }


        try {
            // handle nullOr* methods
            if ('nullOr' === substr($name, 0, 6)) {
                if (null !== $arguments[0]) {
                    $method = lcfirst(substr($name, 6));
                    call_user_func_array([Webmozart::class, $method], $arguments);
                }

                return;
            }

            // handle all* methods
            if ('all' === substr($name, 0, 3)) {
                static::isIterable($arguments[0]);

                $method = lcfirst(substr($name, 3));
                $args = $arguments;

                foreach ($arguments[0] as $entry) {
                    $args[0] = $entry;

                    call_user_func_array([Webmozart::class, $method], $args);
                }

                return;
            }
        
            // all other methods
            call_user_func_array([Webmozart::class, $name], $arguments);
        } catch (\InvalidArgumentException $e) {
            throw new $exception($e->getMessage());
        }

        throw new BadMethodCallException('No such method: ' . $name);
    }
}
