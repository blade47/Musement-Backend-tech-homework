<?php

use Monolog\Logger;

/**
 * Configuration constants
 *
 * @author Alessandro Afloarei <alessandro.afloarei@gmail.com>
 *
 */
class Config
{
    const NUM_OF_DAYS = '2';
    const MUSEMENT_ENDPOINT = 'https://sandbox.musement.com/api/v3/cities';
    const WEATHERAPI_ENDPOINT = 'http://api.weatherapi.com/v1/forecast.json';
    const WEATHERAPI_KEY = '';
    const LOG_LEVEL = Logger::DEBUG;
}
