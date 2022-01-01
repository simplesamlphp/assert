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
 *
 * @method static void string(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void stringNotEmpty(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void integer(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void integerish(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void positiveInteger(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void float(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void numeric(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void natural(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void boolean(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void scalar(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void object(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void resource(mixed $value, string|null $type, string $message = null, Throwable $exception = null)
 * @method static void isCallable(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void isArray(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void isTraversable(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void isArrayAccessible(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void isCountable(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void isIterable(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void isInstanceOf(mixed $value, string|object $class, string $message = null, Throwable $exception = null)
 * @method static void notInstanceOf(mixed $value, string|object $class, string $message = null, Throwable $exception = null)
 * @method static void isInstanceOfAny(mixed $value, array<object|string> $classes, string $message = null, Throwable $exception = null)
 * @method static void isAOf(string|object $value, string $class, string $message = null, Throwable $exception = null)
 * @method static void isNotA(string|object $value, string $class, string $message = null, Throwable $exception = null)
 * @method static void isAnyOf(string|object $value, string[] $classes, string $message = null, Throwable $exception = null)
 * @method static void isEmpty(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void notEmpty(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void null(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void notNull(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void true(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void false(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void notFalse(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void ip(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void ipv4(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void ipv6(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void email(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void uniqueValues(array $values, string $message = null, Throwable $exception = null)
 * @method static void eq(mixed $value, mixed $expect, string $message = null, Throwable $exception = null)
 * @method static void notEq(mixed $value, mixed $expect, string $message = null, Throwable $exception = null)
 * @method static void same(mixed $value, mixed $expect, string $message = null, Throwable $exception = null)
 * @method static void notSame(mixed $value, mixed $expect, string $message = null, Throwable $exception = null)
 * @method static void greaterThan(mixed $value, mixed $limit, string $message = null, Throwable $exception = null)
 * @method static void greaterThanEq(mixed $value, mixed $limit, string $message = null, Throwable $exception = null)
 * @method static void lessThan(mixed $value, mixed $limit, string $message = null, Throwable $exception = null)
 * @method static void lessThanEq(mixed $value, mixed $limit, string $message = null, Throwable $exception = null)
 * @method static void range(mixed $value, mixed $min, mixed $max, string $message = null, Throwable $exception = null)
 * @method static void oneOf(mixed $value, array $values, string $message = null, Throwable $exception = null)
 * @method static void inArray(mixed $value, mixed $values, string $message = null, Throwable $exception = null)
 * @method static void contains(string $value, string $subString, string $message = null, Throwable $exception = null)
 * @method static void notContains(string $value, string $subString, string $message = null, Throwable $exception = null)
 * @method static void notWhitespaceOnly($value, string $message = null, Throwable $exception = null)
 * @method static void startsWith(string $value, string $prefix, string $message = null, Throwable $exception = null)
 * @method static void notStartsWith(string $value, string $prefix, string $message = null, Throwable $exception = null)
 * @method static void startsWithLetter(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void endsWith(string $value, string $suffix, string $message = null, Throwable $exception = null)
 * @method static void notEndsWith(string $value, string $suffix, string $message = null, Throwable $exception = null)
 * @method static void regex(string $value, string $pattern, string $message = null, Throwable $exception = null)
 * @method static void notRegex(string $value, string $pattern, string $message = null, Throwable $exception = null)
 * @method static void unicodeLetters(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void alpha(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void digits(string $value, string $message = null, Throwable $exception = null)
 * @method static void alnum(string $value, string $message = null, Throwable $exception = null)
 * @method static void lower(string $value, string $message = null, Throwable $exception = null)
 * @method static void upper(string $value, string $message = null, Throwable $exception = null)
 * @method static void length(string $value, int $length, string $message = null, Throwable $exception = null)
 * @method static void minLength(string $value, int|float $min, string $message = null, Throwable $exception = null)
 * @method static void maxLength(string $value, int|float $max, string $message = null, Throwable $exception = null)
 * @method static void lengthBetween(string $value, int|float $min, int|float $max, string $message = null, Throwable $exception = null)
 * @method static void fileExists(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void file(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void directory(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void readable(string $value, string $message = null, Throwable $exception = null)
 * @method static void writable(string $value, string $message = null, Throwable $exception = null)
 * @method static void classExists(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void subclassOf(mixed $value, string|object $class, string $message = null, Throwable $exception = null)
 * @method static void interfaceExists(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void implementsInterface(mixed $value, mixed $interface, string $message = null, Throwable $exception = null)
 * @method static void propertyExists(string|object $classOrObject, mixed $property, string $message = null, Throwable $exception = null)
 * @method static void propertyNotExists(string|object $classOrObject, mixed $property, string $message = null, Throwable $exception = null)
 * @method static void methodExists(string|object $classOrObject, mixed $method, string $message = null, Throwable $exception = null)
 * @method static void methodNotExists(string|object $classOrObject, mixed $method, string $message = null, Throwable $exception = null)
 * @method static void keyExists(array $array, string|int $key, string $message = null, Throwable $exception = null)
 * @method static void keyNotExists(array $array, string|int $key, string $message = null, Throwable $exception = null)
 * @method static void validArrayKey($value, string $message = null, Throwable $exception = null)
 * @method static void count(Countable|array $array, int $number, string $message = null, Throwable $exception = null)
 * @method static void minCount(Countable|array $array, int|float $min, string $message = null, Throwable $exception = null)
 * @method static void maxCount(Countable|array $array, int|float $max, string $message = null, Throwable $exception = null)
 * @method static void countBetween(Countable|array $array, int|float $min, int|float $max, string $message = null, Throwable $exception = null)
 * @method static void isList(mixed $array, string $message = null, Throwable $exception = null)
 * @method static void isNonEmptyList(mixed $array, string $message = null, Throwable $exception = null)
 * @method static void isMap(mixed $array, string $message = null, Throwable $exception = null)
 * @method static void isNonEmptyMap(mixed $array, string $message = null, Throwable $exception = null)
 * @method static void uid(string $value, string $message = null, Throwable $exception = null)
 * @method static void throws(Closure $expression, string $class, string $message = null, Throwable $exception = null)
 *
 * @method static void nullOrString(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allString(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrStringNotEmpty(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allOrStringNotEmpty(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrInteger(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allInteger(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrIntegerish(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allIntegerish(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrPositiveInteger(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allPositiveInteger(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrFloat(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allFloat(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrNumeric(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allNumeric(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrNatural(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allNatural(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrBoolean(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allBoolean(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrScalar(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allScalar(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrObject(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allObject(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrResource(mixed $value, string|null $type, string $message = null, Throwable $exception = null)
 * @method static void allResource(mixed $value, string|null $type, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsCallable(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allIsCallable(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsArray(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allIsArray(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsTraversable(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allIsTraversable(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsArrayAccessible(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allIsArrayAccessible(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsCountable(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allIsCountable(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsIterable(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allIsIterable(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsInstanceOf(mixed $value, string|object $class, string $message = null, Throwable $exception = null)
 * @method static void allIsInstanceOf(mixed $value, string|object $class, string $message = null, Throwable $exception = null)
 * @method static void nullOrNotInstanceOf(mixed $value, string|object $class, string $message = null, Throwable $exception = null)
 * @method static void allNotInstanceOf(mixed $value, string|object $class, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsInstanceOfAny(mixed $value, array<object|string> $classes, string $message = null, Throwable $exception = null)
 * @method static void allIsInstanceOfAny(mixed $value, array<object|string> $classes, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsAOf(object|string|null $value, string $class, string $message = null, Throwable $exception = null)
 * @method static void allIsAOf(object|string|null $value, string $class, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsNotA(object|string|null $value, string $class, string $message = null, Throwable $exception = null)
 * @method static void allIsNotA(iterable<object|string> $value, string $class, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsAnyOf(object|string|null $value, string[] $classes, string $message = null, Throwable $exception = null)
 * @method static void allIsAnyOf(iterable<object|string> $value, string[] $classes, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsEmpty(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allIsEmpty(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrNotEmpty(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allNotEmpty(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allNull(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allNotNull(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrTrue(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allTrue(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrFalse(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allFalse(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrNotFalse(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allNotFalse(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrIp(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allIp(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrIpv4(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allIpv4(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrIpv6(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allIpv6(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrEmail(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allEmail(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrUniqueValues(array|null $values, string $message = null, Throwable $exception = null)
 * @method static void allUniqueValues(iterable<array> $values, string $message = null, Throwable $exception = null)
 * @method static void nullOrEq(mixed $value, mixed $expect, string $message = null, Throwable $exception = null)
 * @method static void allEq(mixed $value, mixed $expect, string $message = null, Throwable $exception = null)
 * @method static void nullOrNotEq(mixed $value, mixed $expect, string $message = null, Throwable $exception = null)
 * @method static void allNotEq(mixed $value, mixed $expect, string $message = null, Throwable $exception = null)
 * @method static void nullOrSame(mixed $value, mixed $expect, string $message = null, Throwable $exception = null)
 * @method static void allSame(mixed $value, mixed $expect, string $message = null, Throwable $exception = null)
 * @method static void nullOrNotSame(mixed $value, mixed $expect, string $message = null, Throwable $exception = null)
 * @method static void allNotSame(mixed $value, mixed $expect, string $message = null, Throwable $exception = null)
 * @method static void nullOrGreaterThan(mixed $value, mixed $limit, string $message = null, Throwable $exception = null)
 * @method static void allGreaterThan(mixed $value, mixed $limit, string $message = null, Throwable $exception = null)
 * @method static void nullOrGreaterThanEq(mixed $value, mixed $limit, string $message = null, Throwable $exception = null)
 * @method static void allGreaterThanEq(mixed $value, mixed $limit, string $message = null, Throwable $exception = null)
 * @method static void nullOrLessThan(mixed $value, mixed $limit, string $message = null, Throwable $exception = null)
 * @method static void allLessThan(mixed $value, mixed $limit, string $message = null, Throwable $exception = null)
 * @method static void nullOrLessThanEq(mixed $value, mixed $limit, string $message = null, Throwable $exception = null)
 * @method static void allLessThanEq(mixed $value, mixed $limit, string $message = null, Throwable $exception = null)
 * @method static void nullOrRange(mixed $value, mixed $min, mixed $max, string $message = null, Throwable $exception = null)
 * @method static void allRange(mixed $value, mixed $min, mixed $max, string $message = null, Throwable $exception = null)
 * @method static void nullOrOneOf(mixed $value, array $values, string $message = null, Throwable $exception = null)
 * @method static void allOneOf(mixed $value, array $values, string $message = null, Throwable $exception = null)
 * @method static void nullOrInArray(mixed $value, array $values, string $message = null, Throwable $exception = null)
 * @method static void allInArray(mixed $value, array $values, string $message = null, Throwable $exception = null)
 * @method static void nullOrContains(string|null $value, string $subString, string $message = null, Throwable $exception = null)
 * @method static void allContains(iterable<string> $value, string $subString, string $message = null, Throwable $exception = null)
 * @method static void nullOrNotContains(string|null $value, string $subString, string $message = null, Throwable $exception = null)
 * @method static void allNotContains(iterable<string> $value, string $subString, string $message = null, Throwable $exception = null)
 * @method static void nullOrWhitespaceOnly(string|null $value, string $message = null, Throwable $exception = null)
 * @method static void allWhitespaceOnly(iterable<string> $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrStartsWith(string|null $value, string $prefix, string $message = null, Throwable $exception = null)
 * @method static void allStartsWith(iterable<string> $value, string $prefix, string $message = null, Throwable $exception = null)
 * @method static void nullOrNotStartsWith(string|null $value, string $prefix, string $message = null, Throwable $exception = null)
 * @method static void allNotStartsWith(iterable<string> $value, string $prefix, string $message = null, Throwable $exception = null)
 * @method static void nullOrStartsWithLetter(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allStartsWithLetter(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrEndsWith(string|null $value, string $suffix, string $message = null, Throwable $exception = null)
 * @method static void allEndsWith(iterable<string> $value, string $suffix, string $message = null, Throwable $exception = null)
 * @method static void nullOrNotEndsWith(string|null $value, string $suffix, string $message = null, Throwable $exception = null)
 * @method static void allNotEndsWith(iterable<string> $value, string $suffix, string $message = null, Throwable $exception = null)
 * @method static void nullOrRegex(string|null $value, string $prefix, string $message = null, Throwable $exception = null)
 * @method static void allRegEx(iterable<string> $value, string $prefix, string $message = null, Throwable $exception = null)
 * @method static void nullOrNotRegex(string|null $value, string $prefix, string $message = null, Throwable $exception = null)
 * @method static void allNotRegEx(iterable<string> $value, string $prefix, string $message = null, Throwable $exception = null)
 * @method static void nullOrUnicodeLetters(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allUnicodeLetters(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrAlpha(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allAlpha(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrDigits(string|null $value, string $message = null, Throwable $exception = null)
 * @method static void allDigits(iterable<string> $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrAlnum(string|null $value, string $message = null, Throwable $exception = null)
 * @method static void allAlnum(iterable<string> $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrLower(string|null $value, string $message = null, Throwable $exception = null)
 * @method static void allLower(iterable<string> $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrUpper(string|null $value, string $message = null, Throwable $exception = null)
 * @method static void allUpper(iterable<string> $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrLength(string|null $value, int $length, string $message = null, Throwable $exception = null)
 * @method static void allLength(iterable<string> $value, int $length, string $message = null, Throwable $exception = null)
 * @method static void nullOrMinLength(string|null $value, int|float $min, string $message = null, Throwable $exception = null)
 * @method static void allMinLength(iterable<string> $value, int|float $min, string $message = null, Throwable $exception = null)
 * @method static void nullOrMaxLength(string|null $value, int|float $max, string $message = null, Throwable $exception = null)
 * @method static void allMaxLength(iterable<string> $value, int|float $max, string $message = null, Throwable $exception = null)
 * @method static void nullOrLengthBetween(string|null $value, int|float $min, int|float $max, string $message = null, Throwable $exception = null)
 * @method static void allLengthBetween(iterable<string> $value, int|float $min, int|float $max, string $message = null, Throwable $exception = null)
 * @method static void nullOrFileExists(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allFileExists(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrFile(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allFile(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrDirectory(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allDirectory(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrReadable(string|null $value, string $message = null, Throwable $exception = null)
 * @method static void allReadable(iterable<string> $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrWritable(string|null $value, string $message = null, Throwable $exception = null)
 * @method static void allWritable(iterable<string> $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrClassExists(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allClassExists(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrSubclassOf(mixed $value, string|object $class, string $message = null, Throwable $exception = null)
 * @method static void allSubclassOf(mixed $value, string|object $class, string $message = null, Throwable $exception = null)
 * @method static void nullOrInterfaceExists(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allInterfaceExists(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrImplementsInterface(mixed $value, mixed $interface, string $message = null, Throwable $exception = null)
 * @method static void allImplementsInterface(mixed $value, mixed $interface, string $message = null, Throwable $exception = null)
 * @method static void nullOrPropertyExists(string|object|null $classOrObject, mixed $property, string $message = null, Throwable $exception = null)
 * @method static void allPropertyExists(iterable<string|object> $classOrObject, mixed $property, string $message = null, Throwable $exception = null)
 * @method static void nullOrPropertyNotExists(string|object|null $classOrObject, mixed $property, string $message = null, Throwable $exception = null)
 * @method static void allPropertyNotExists(iterable<string|object> $classOrObject, mixed $property, string $message = null, Throwable $exception = null)
 * @method static void nullOrMethodExists(string|object|null $classOrObject, mixed $method, string $message = null, Throwable $exception = null)
 * @method static void allMethodExists(iterable<string|object> $classOrObject, mixed $method, string $message = null, Throwable $exception = null)
 * @method static void nullOrMethodNotExists(string|object|null $classOrObject, mixed $method, string $message = null, Throwable $exception = null)
 * @method static void allMethodNotExists(iterable<string|object> $classOrObject, mixed $method, string $message = null, Throwable $exception = null)
 * @method static void nullOrKeyExists(array|null $array, string|int $key, string $message = null, Throwable $exception = null)
 * @method static void allKeyExists(iterable<array> $array, string|int $key, string $message = null, Throwable $exception = null)
 * @method static void nullOrKeyNotExists(array|null $array, string|int $key, string $message = null, Throwable $exception = null)
 * @method static void allKeyNotExists(iterable<array> $array, string|int $key, string $message = null, Throwable $exception = null)
 * @method static void nullOrValidArrayKey(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void allValidArrayKey(mixed $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrCount(Countable|array|null $array, int $number, string $message = null, Throwable $exception = null)
 * @method static void allCount(iterable<Countable|array> $array, int $number, string $message = null, Throwable $exception = null)
 * @method static void nullOrMinCount(Countable|array|null $array, int|float $min, string $message = null, Throwable $exception = null)
 * @method static void allMinCount(iterable<Countable|array> $array, int|float $min, string $message = null, Throwable $exception = null)
 * @method static void nullOrMaxCount(Countable|array|null $array, int|float $max, string $message = null, Throwable $exception = null)
 * @method static void allMaxCount(iterable<Countable|array> $array, int|float $max, string $message = null, Throwable $exception = null)
 * @method static void nullOrCountBetween(Countable|array|null $array, int|float $min, int|float $max, string $message = null, Throwable $exception = null)
 * @method static void allCountBetween(iterable<Countable|array> $array, int|float $min, int|float $max, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsList(mixed $array, string $message = null, Throwable $exception = null)
 * @method static void allIsList(mixed $array, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsNonEmptyList(mixed $array, string $message = null, Throwable $exception = null)
 * @method static void allIsNonEmptyList(mixed $array, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsMap(mixed $array, string $message = null, Throwable $exception = null)
 * @method static void allIsMap(mixed $array, string $message = null, Throwable $exception = null)
 * @method static void nullOrIsNonEmptyMap(mixed $array, string $message = null, Throwable $exception = null)
 * @method static void allIsNonEmptyMap(mixed $array, string $message = null, Throwable $exception = null)
 * @method static void nullOrUuid(string|null $value, string $message = null, Throwable $exception = null)
 * @method static void allUuid(iterable<string> $value, string $message = null, Throwable $exception = null)
 * @method static void nullOrThrows(Closure|null $expression, string $class, string $message = null, Throwable $exception = null)
 * @method static void allThrows(iterable<Closure> $expression, string $class, string $message = null, Throwable $exception = null)
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
