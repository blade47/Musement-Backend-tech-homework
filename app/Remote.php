<?php

namespace App;

use Config;

/**
 * Remote handler
 *
 * Class that allow to retrieve the required information from the remotes endpoints
 *
 * @author Alessandro Afloarei <alessandro.afloarei@gmail.com>
 *
 */
class Remote
{
    private $conn_;

    public function __construct()
    {
        Log::info('Initializing a new Connection instance', [
            'file' => __FILE__,
            'line' => __LINE__
        ]);
        $this->conn_ = new Connection();
    }

    /**
     * Get the Musement Cities from the Musement API Endpoint
     *
     * @return   array      Requested cities
     */
    public function getMusementCities()
    {
        Log::info('Executing remote GET to Musement API', [
            'file' => __FILE__,
            'line' => __LINE__
        ]);

        return $this->conn_->get(Config::MUSEMENT_ENDPOINT);
    }
    
    /**
     * Get the Forecasts for the chosen latitude and longitude for
     * the next selected days from the Weatherapi API Endpoint
     *
     * @param    string      $latitude       Latitude of the city
     * @param    string      $longitude      Longitude of the city
     * @param    string      $num_of_days    Number of days (default: 2)
     *
     * @return   array       Requested forecasts
     */
    public function getForecastsFor($latitude, $longitude, $num_of_days = '2')
    {
        // Setting up the weatherapi required parameters
        $weatherapi_params = [
            'key' => Config::WEATHERAPI_KEY,
            'q' =>  $latitude. ',' . $longitude,
            'days' => $num_of_days
        ];

        Log::info('Executing remote GET to Weatherapi API', [
            'file' => __FILE__,
            'line' => __LINE__
        ]);

        return $this->conn_->get(Config::WEATHERAPI_ENDPOINT, $weatherapi_params);
    }
}
