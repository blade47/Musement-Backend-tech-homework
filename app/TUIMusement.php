<?php

namespace App;

use App\Validator\Validator;

/**
 * TUIMusement tech homework
 *
 * Gets the list of the cities from Musement's API for each city gets the
 * forecast for the next 2 days using http://api.weatherapi.com and print to
 * STDOUT "Processed city [city name] | [weather today] - [wheather tomorrow]"
 *
 * Example:
 *  Processed city Milan | Heavy rain - Partly cloudy
 *  Processed city Rome | Sunny - Sunny
 *
 * @author Alessandro Afloarei <alessandro.afloarei@gmail.com>
 *
 */
class TUIMusement
{
    private $remote_;

    public function __construct()
    {
        Log::info('Initializing a new Remote instance', [
            'file' => __FILE__,
            'line' => __LINE__
        ]);
        $this->remote_ = new Remote();
    }

    /**
     * Run the TuiMusement App
     *
     * @param  string   $num_of_days   Number of days to predict
     *
     */
    public function run($num_of_days)
    {
        fclose(STDOUT);
        $STDOUT = fopen('app.log', 'wb');
        
        Log::info('Retrieving all the cities from Musement API', [
            'file' => __FILE__,
            'line' => __LINE__
        ]);

        $all_cities = $this->remote_->getMusementCities();

        // Foreach city take the forecasts info
        foreach ($all_cities as $city) {
            $res = $this->processCity($city, $num_of_days);

            Log::info('Writing to STDOUT the result', [
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            echo $res . PHP_EOL;
        }
    }

    /**
     * Process a given City object
     *
     * @param   array   $city           City model given by TUIMusement API Endpoint
     * @param   string  $num_of_days    Number of days to process
     *
     * @return  string  Processed city [city name] | [weather of the day] - ...
     *
     */
    private function processCity($city, $num_of_days)
    {
        // Checking consistency of the city json obj
        $valid = Validator::validate($city, [
            'id' => 'required',
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        if ($valid) {
            $city_name = $city['name'];
            $latitude = strval($city['latitude']);
            $longitude = strval($city['longitude']);
    
            Log::debug('Creating the result string with the city info', [
                'city' => $city_name,
                'file' => __FILE__,
                'line' => __LINE__
            ]);
            
            // Initializing the result string
            $res = 'Processed city ' . $city_name . ' | ';
    
            Log::info('Retrieving the forecasts info from Weatherapi API', [
                'city' => $city_name,
                'file' => __FILE__,
                'line' => __LINE__
            ]);
            $forecasts = $this->remote_->getForecastsFor($latitude, $longitude, $num_of_days);
            $res .= $this->processForecasts($forecasts);

            return $res;
        } else {
            Log::warning('Required parameters not provided for Object ID: ' . $city['id'], [
                'file' => __FILE__,
                'line' => __LINE__
            ]);
            return '';
        }
    }

    /**
     * Process a given Forecasts array
     *
     * @param   array   $forecasts      Forecasts model given by Weatherapi API Endpoint
     *
     * @return  string  [weather of the day] - ...
     *
     */
    private function processForecasts(array $forecasts)
    {
        // Checking consistency of the forecasts json obj
        $valid = Validator::validate($forecasts, [
            'forecast.forecastday' => 'isArray'
        ]);
        if ($valid) {
            $len = count($forecasts['forecast']['forecastday']);
            $count = 1;
            $res = '';

            Log::debug('Updating the result string with the forecast of each day', [
                'file' => __FILE__,
                'line' => __LINE__
            ]);

            // Foreach day take the forecast info
            foreach ($forecasts['forecast']['forecastday'] as $daily_forecast) {
                
                // Checking consistency of forecast json obj
                $valid = Validator::validate($daily_forecast, [
                    'day.condition.text' => 'required'
                ]);
                if ($valid) {
                    $res .= $daily_forecast['day']['condition']['text'];
                } else {
                    $res .= 'Condition not available';
                }
                
                // Final '-' only if not the last forecast
                if ($count != $len) {
                    $res .= ' - ';
                }

                $count += 1;
            }

            return $res;
        } else {
            Log::warning('Forecast not provided or not valid', [
                'file' => __FILE__,
                'line' => __LINE__
            ]);
            return '';
        }
    }
}
