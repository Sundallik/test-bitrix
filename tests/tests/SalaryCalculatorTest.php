<?php
namespace Tests;

use App\SalaryCalculator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SalaryCalculatorTest extends TestCase {

    #[DataProvider('dataProvider')]
    public function testCalculate($salary, $expected): void {
        $salaryCalculator = new SalaryCalculator();
        $result = $salaryCalculator->calculate($salary);
        self::assertEquals($expected, $result);
    }

    public static function dataProvider(): array {
        return [
            [10, 9],
            [20, 18],
            [10.5, 9.45],
            [15.5, 13.95]
        ];
    }
}