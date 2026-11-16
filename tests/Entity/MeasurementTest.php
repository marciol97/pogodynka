<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class MeasurementTest extends TestCase
{
    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['-40', '-40'],
            ['21', '69.8'],
            ['37', '98.6'],
            ['-17.5', '0.5'],
            ['12.5', '54.5'],
            ['25.5', '77.9'],
        ];
    }

    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $measurement = new Measurement();
        $measurement->setTempCelsius($celsius);
        $this->assertEquals($expectedFahrenheit, $measurement->getFahrenheit());
    }
}
