<?php

declare(strict_types=1);

namespace SimpleSAML\Assert;

use InvalidArgumentException;

use function base64_decode;
use function base64_encode;
use function filter_var;
use function sprintf;
use function strlen;

/**
 * @package simplesamlphp/assert
 */
trait Base64Trait
{
    private static string $base64_regex =
      '/^(?:[a-z0-9+\/]{4})*(?:[a-z0-9+\/][AQgw]==|[a-z0-9+\/]{2}[AEIMQUYcgkosw048]=)?$/i';


    /***********************************************************************************
     *  NOTE:  Custom assertions may be added below this line.                         *
     *         They SHOULD be marked as `protected` to ensure the call is forced       *
     *          through __callStatic().                                                *
     *         Assertions marked `public` are called directly and will                 *
     *          not handle any custom exception passed to it.                          *
     ***********************************************************************************/


    /**
     * Note: This test is not bullet-proof but prevents a string containing illegal characters
     * from being passed and ensures the string roughly follows the correct format for a Base64 encoded string
     */
    protected static function validBase64(string $value, string $message = ''): string
    {
        $result = true;

        if ($value === '') {
            // The empty string is valid
            $result = true;
        } elseif (strlen($value) % 4 !== 0) {
            // Encoded string length must be a multiple of 4 (or 0 for empty string)
            $result = false;
        } elseif (
            filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => self::$base64_regex]]) === false
        ) {
            // Encoded string must not contain illegal characters and 0, 1 or 2 padding characters.
            $result = false;
        } else {
            // Defense in depth: strict decoding fails on illegal characters or incorrect padding
            $decoded = base64_decode($value, true);
            if ($decoded === false) {
                // Strict decoding failed
                $result = false;
            } elseif (base64_encode($decoded) !== $value) {
                // re-encoding produced a different string
                $result = false;
            }
        }

        if ($result === false) {
            throw new InvalidArgumentException(sprintf(
                $message ?: '\'%s\' is not a valid Base64 encoded string',
                $value,
            ));
        }

        return $value;
    }
}
