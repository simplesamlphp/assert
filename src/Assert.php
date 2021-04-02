<?php

declare(strict_types=1);

namespace SimpleSAML\Assert;

use BadMethodCallException;
use InvalidArgumentException;
use Throwable;
use Webmozart\Assert\Assert as Webmozart;

/**
 * Webmozart\Assert wrapper class
 *
 * @package simplesamlphp/assert
 */
final class Assert
{
    private static string $base64_regex = '/^(?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=)?$/';

    /**
     * @param string $name
     * @param array $arguments
     */
    public static function __callStatic($name, $arguments): void
    {
        // Handle Exception-parameter
        $exception = AssertionFailedException::class;
        $last = end($arguments);
        if (is_string($last) && class_exists($last) && is_subclass_of($last, Throwable::class)) {
            $exception = $last;

            array_pop($arguments);
        }

        try {
            call_user_func_array([Webmozart::class, $name], $arguments);
            return;
        } catch (InvalidArgumentException $e) {
            throw new $exception($e->getMessage());
        }
    }


    /**
     * Note: This test is not bullet-proof but prevents a string containing illegal characters
     * from being passed and ensures the string roughly follows the correct format for a Base64 encoded string
     *
     * @param string $value
     * @param string $message
     */
    public static function stringPlausibleBase64(string $value, $message = ''): void
    {
        $result = true;

        if (filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => self::$base64_regex]]) === false) {
            $result = false;
        } else {
            $decoded = base64_decode($value, true);
            if ($decoded === false) {
                $result = false;
            } elseif (base64_encode($decoded) !== $value) {
                $result = false;
            }
        }

        if ($result === false) {
            throw new AssertionFailedException(
                sprintf(
                    $message ?: '\'%s\' is not a valid Base64 encoded string',
                    $value
                )
            );
        }
    }
}
