<?php

declare(strict_types=1);

namespace SimpleSAML\Assert;

use InvalidArgumentException;
use Uri\InvalidUriException;
use Uri\Rfc3986\Uri;
use Uri\WhatWg\InvalidUrlException;
use Uri\WhatWg\Url;

use function sprintf;
use function strlen;
use function substr;

/**
 * @package simplesamlphp/assert
 */
trait URITrait
{
    /***********************************************************************************
     *  NOTE:  Custom assertions may be added below this line.                         *
     *         They SHOULD be marked as `protected` to ensure the call is forced       *
     *          through __callStatic().                                                *
     *         Assertions marked `public` are called directly and will                 *
     *          not handle any custom exception passed to it.                          *
     ***********************************************************************************/

    private static Uri|Url $uri;


    /**
     */
    protected static function validURN(string $value, string $message = ''): string
    {
        try {
            self::$uri = new Uri($value);
        } catch (InvalidUriException $e) {
            throw new InvalidArgumentException(sprintf(
                $message ?: '\'%s\' is not a valid RFC3986 compliant URI',
                $value,
            ));
        }

        if (
            self::$uri->getScheme() !== 'urn'
            || self::$uri->getPath() !== substr($value, strlen(self::$uri->getScheme()) + 1)
        ) {
            throw new InvalidArgumentException(sprintf(
                $message ?: '\'%s\' is not a valid RFC8141 compliant URN',
                $value,
            ));
        }

        return $value;
    }


    /**
     */
    protected static function validURL(string $value, string $message = ''): string
    {
        try {
            self::$uri = new Url($value);
        } catch (InvalidUrlException $e) {
            throw new InvalidArgumentException(sprintf(
                $message ?: '\'%s\' is not a valid WhatWg compliant URI',
                $value,
            ));
        }

        if (self::$uri->getScheme() !== 'http' && self::$uri->getScheme() !== 'https') {
            throw new InvalidArgumentException(sprintf(
                $message ?: '\'%s\' is not a valid WhatWg compliant URL',
                $value,
            ));
        }

        return $value;
    }


    /**
     */
    protected static function validURI(string $value, string $message = ''): string
    {
        $failure = false;
        try {
            self::$uri = new Uri($value);
        } catch (InvalidUriException $e) {
            $failure = true;
        }

        if ($failure === true) {
            try {
                self::$uri = new Url($value);
            } catch (InvalidUrlException $e) {
                throw new InvalidArgumentException(sprintf(
                    $message ?: '\'%s\' is not a valid WhatWg compliant URL',
                    $value,
                ));
            } finally {
                $failure = false;
            }

            if ($failure === true) {
                throw new InvalidArgumentException(sprintf(
                    $message ?: '\'%s\' is not a valid RFC3986 compliant URI',
                    $value,
                ));
            }
        }

        return $value;
    }


    /**
     * For convenience and efficiency, to get the Uri-object from the last assertion.
     */
    public static function getUri(): Uri
    {
        return self::$uri;
    }
}
