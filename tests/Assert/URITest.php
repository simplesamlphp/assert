<?php

declare(strict_types=1);

namespace SimpleSAML\Test\Assert;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Assert\Assert;
use SimpleSAML\Assert\AssertionFailedException;

/**
 * Class \SimpleSAML\Assert\URITest
 *
 * @package simplesamlphp/assert
 */
#[CoversClass(Assert::class)]
final class URITest extends TestCase
{
    /**
     * @param boolean $shouldPass
     * @param string $uri
     */
    #[DataProvider('provideURI')]
    public function testValidURI(bool $shouldPass, string $uri): void
    {
        try {
            Assert::validURI($uri);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @param boolean $shouldPass
     * @param string $url
     */
    #[DataProvider('provideURL')]
    public function testValidURL(bool $shouldPass, string $url): void
    {
        try {
            Assert::validURL($url);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @param boolean $shouldPass
     * @param string $urn
     */
    #[DataProvider('provideURN')]
    public function testValidURN(bool $shouldPass, string $urn): void
    {
        try {
            Assert::validURN($urn);
            $this->assertTrue($shouldPass);
        } catch (AssertionFailedException $e) {
            $this->assertFalse($shouldPass);
        }
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideURI(): array
    {
        return [
            'urn' => [true, 'urn:x-simplesamlphp:phpunit'],
            'same-doc' => [true, '#_53d830ab1be17291a546c95c7f1cdf8d3d23c959e6'],
            'url' => [true, 'https://www.simplesamlphp.org'],
            'invalid_char' => [false, 'https://a⒈com'],
            'intl' => [true, 'https://niño.com'],
            'spn' => [true, 'spn:a4cf592f-a64c-46ff-a788-b260f474525b'],
            'typos' => [true, 'https//www.uni.l/en/'],
            'spaces' => [true, 'this is silly'],
        ];
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideURL(): array
    {
        return [
            'url' => [true, 'https://www.simplesamlphp.org'],
            'same-doc' => [false, '#_53d830ab1be17291a546c95c7f1cdf8d3d23c959e6'],
            'urn' => [false, 'urn:x-simplesamlphp:phpunit'],
            'invalid_char' => [false, 'https://a⒈com'],
            'intl' => [true, 'https://niño.com'],
            'spn' => [false, 'spn:a4cf592f-a64c-46ff-a788-b260f474525b'],
            'typos' => [false, 'https//www.uni.l/en/'],
        ];
    }


    /**
     * @return array<string, array{0: bool, 1: string}>
     */
    public static function provideURN(): array
    {
        return [
            'urn' => [true, 'urn:x-simplesamlphp:phpunit'],
            'url' => [false, 'https://www.simplesamlphp.org'],
            'same-doc' => [false, '#_53d830ab1be17291a546c95c7f1cdf8d3d23c959e6'],
            'invalid_char' => [false, 'https://a⒈com'],
            'intl' => [false, 'https://niño.com'],
            'spn' => [false, 'spn:a4cf592f-a64c-46ff-a788-b260f474525b'],
            'typos' => [false, 'https//www.uni.l/en/'],
        ];
    }
}
