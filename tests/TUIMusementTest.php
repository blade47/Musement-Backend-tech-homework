<?php

namespace Tests;

use App\TUIMusement;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotFalse;

final class TUIMusementTest extends TestCase
{
    public function testProcessCity()
    {
        $processCity = $this->getMethod('processCity');
        $obj = new TUIMusement();
        $res = $processCity->invokeArgs($obj, [
            array(
                'id' => 1,
                'name' => 'Paris',
                'latitude' => '48.866',
                'longitude' => '2.355'
            ),
            '2'
        ]);

        assertNotFalse(strpos($res, 'Processed city Paris | '));
    }

    public function testProcessCityRequiredArgumentMissing()
    {
        $processCity = $this->getMethod('processCity');
        $obj = new TUIMusement();
        $res = $processCity->invokeArgs($obj, [
            array(
                'id' => 1,
                'name' => 'Paris',
                'latitude' => '48.866',
            ),
            '2'
        ]);

        assertFalse(strpos($res, 'Processed city Paris | '));
        assertEquals('', $res);
    }

    public function testProcessForecasts()
    {
        $processForecasts = $this->getMethod('processForecasts');
        $obj = new TUIMusement();
        $res = $processForecasts->invokeArgs($obj, [
            array(
                'forecast' =>
                array(
                    'forecastday' =>
                    array(
                        0 =>
                        array(
                            'date' => '2021-01-24',
                            'day' => array(
                                'condition' => array(
                                    'text' => 'Overcast',
                                ),
                            ),
                        ),
                        1 =>
                        array(
                            'date' => '2021-01-25',
                            'day' => array(
                                'condition' => array(
                                    'text' => 'Overcast',
                                ),
                            ),
                        ),
                    ),
                ),
            )
        ]);

        assertNotFalse(strpos($res, 'Overcast'));
    }

    public function testProcessForecastsRequiredArgumentMissing()
    {
        $processForecasts = $this->getMethod('processForecasts');
        $obj = new TUIMusement();
        $res = $processForecasts->invokeArgs($obj, [
            array(
                'forecast' =>
                array(
                    'forecastday' =>
                    array(
                        0 =>
                        array(
                            'date' => '2021-01-24'
                        )
                    ),
                ),
            )
        ]);

        assertFalse(strpos($res, 'Overcast'));
    }

    public function testProcessForecastsBadRequiredArgument()
    {
        $processForecasts = $this->getMethod('processForecasts');
        $obj = new TUIMusement();
        $res = $processForecasts->invokeArgs($obj, [
            array(
                'forecast' =>
                array(
                    'forecastday' => 'NULL'
                )
            )
        ]);

        assertFalse(strpos($res, 'Overcast'));
    }

    private function getMethod($name)
    {
        $class = new ReflectionClass('App\TUIMusement');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
