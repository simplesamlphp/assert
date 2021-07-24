<?php

declare(strict_types=1);

namespace SimpleSAML\Assert;

use BadMethodCallException;
use DateTime;
use InvalidArgumentException;
use Throwable;
use Webmozart\Assert\Assert as Webmozart;

use function array_pop;
use function base64_decode;
use function base64_encode;
use function call_user_func_array;
use function end;
use function filter_var;
use function is_string;
use function is_subclass_of;
use function method_exists;
use function sprintf;

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
            if (method_exists(static::class, $name)) {
                call_user_func_array([static::class, $name], $arguments);
            } else {
                call_user_func_array([Webmozart::class, $name], $arguments);
            }
            return;
        } catch (InvalidArgumentException $e) {
            throw new $exception($e->getMessage());
        }
    }


    /***********************************************************************************
     *  NOTE:  Custom assertions may be added below this line.                         *
     *         They SHOULD be marked as `private` to ensure the call is forced         *
     *          through __callStatic().                                                *
     *         Assertions marked `public` are called directly and will                 *
     *          not handle any custom exception passed to it.                          *
     ***********************************************************************************/


    /**
     * Note: This test is not bullet-proof but prevents a string containing illegal characters
     * from being passed and ensures the string roughly follows the correct format for a Base64 encoded string
     *
     * @param string $value
     * @param string $message
     */
    private static function stringPlausibleBase64(string $value, $message = ''): void
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
            throw new InvalidArgumentException(
                sprintf(
                    $message ?: '\'%s\' is not a valid Base64 encoded string',
                    $value
                )
            );
        }
    }


    /**
     * @param string $value
     * @param string $message
     */
    private static function validDateTime(string $value, $message = ''): void
    {
        if (DateTime::createFromFormat(DateTime::ISO8601, $value) === false) {
            throw new InvalidArgumentException(
                sprintf(
                    $message ?: '\'%s\' is not a valid DateTime',
                    $value
                )
            );
        }
    }


    /**
     * @param string $value
     * @param string $message
     */
    private static function validDateTimeZulu(string $value, $message = ''): void
    {
        $dateTime = DateTime::createFromFormat(DateTime::ISO8601, $value);
        if ($dateTime === false) {
            throw new InvalidArgumentException(
                sprintf(
                    $message ?: '\'%s\' is not a valid DateTime',
                    $value
                )
            );
        } elseif ($dateTime->getTimezone()->getName() !== 'Z') {
            throw new InvalidArgumentException(
                sprintf(
                    $message ?: '\'%s\' is not a DateTime expressed in the UTC timezone using the \'Z\' timezone identifier.',
                    $value
                )
            );
        }
    }
}
