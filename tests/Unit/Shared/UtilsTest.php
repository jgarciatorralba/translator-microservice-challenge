<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared;

use App\Shared\Utils;
use DateTime;
use DateTimeInterface;
use Exception;
use PHPUnit\Framework\TestCase;

final class UtilsTest extends TestCase
{
    public function testDateToString(): void
    {
        $date = new DateTime();
        $dateToString = Utils::dateToString($date);

        $this->assertIsString($dateToString);
        $this->assertMatchesRegularExpression(
            '/\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2]\d|3[0-1])T[0-2]\d:[0-5]\d:[0-5]\d[+-][0-2]\d:[0-5]\d/',
            $dateToString
        );
    }

    public function testStringToDate(): void
    {
        $date = Utils::stringToDate('2010-10-10');
        $this->assertInstanceOf(DateTimeInterface::class, $date);

        $this->expectException(Exception::class);
        $date = Utils::stringToDate('invalid-date-string');
    }

    public function testExtractClassName(): void
    {
        $className = Utils::extractClassName(Utils::class);
        $this->assertEquals('Utils', $className);
    }

    /**
     * @dataProvider dataToSnakeCase
     * @param string $expectedResult
     */
    public function testToSnakeCase(
        string $stringToConvert,
        string $expectedResult
    ): void {
        $snakeCase = Utils::toSnakeCase($stringToConvert);
        $this->assertEquals($expectedResult, $snakeCase);
    }

    /**
     * @return array<string, string[]>
     */
    public static function dataToSnakeCase(): array
    {
        return [
            'camel case' => ['camelCaseString', 'camel_case_string'],
            'pascal case' => ['PascalCaseString', 'pascal_case_string']
        ];
    }
}
