<?php

namespace App;

use Exception;

/**
 * Connection handler
 *
 * Class that handle GET requests
 *
 * @author Alessandro Afloarei <alessandro.afloarei@gmail.com>
 *
 */
class Connection
{
    /**
     *
     * @param       string     $endpoint       Endpoint
     * @param       array      $params         Request parameters
     *
     * @throws      Exception   If the remote connection failed
     * @return      array       Result of the GET
     */
    public function get($endpoint, array $params = [])
    {
        Log::debug('Initializing a new Curl session', [
            'file' => __FILE__,
            'line' => __LINE__
        ]);

        $ch = $this->curlInit($endpoint, $params);
        $res = json_decode(curl_exec($ch), true);
    
        // Checking for errors
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            Log::error('Error raised during the remote GET', [
                'error' => $error_msg,
                'file' => __FILE__,
                'line' => __LINE__
            ]);
        }
    
        Log::debug('Closing the Curl session', [
            'file' => __FILE__,
            'line' => __LINE__
        ]);
        curl_close($ch);
    
        if (isset($error_msg)) {
            Log::debug('Raising the error up', [
                'file' => __FILE__,
                'line' => __LINE__
            ]);
            throw new Exception($error_msg);
        }
    
        Log::debug('Result retrieved successfully', [
            'file' => __FILE__,
            'line' => __LINE__
        ]);

        return $res;
    }

    /**
     * Initializes a new session with the given parameters
     * and return a cURL handle
     *
     * @param    string          $endpoint       Clean endpoint where the curl must point
     * @param    array           $params         Request parameters
     *
     * @return   CurlHandle      Requested CurlHandle
    */
    private function curlInit($endpoint, array $params = [])
    {
        Log::debug('Building the final endpoint', [
            'file' => __FILE__,
            'line' => __LINE__
        ]);
        $count = 0;
        foreach ($params as $key => $value) {
            // If the beginning of the parameters append '?' else '&'
            if ($count == 0) {
                $endpoint .= '?';
            } else {
                $endpoint .= '&';
            }

            // Each key-value is connected by '='
            $endpoint .= $key . '=' . $value;
            $count += 1;
        }
    
        Log::debug('Calling the curl_init function', [
            'file' => __FILE__,
            'line' => __LINE__
        ]);
        $ch = curl_init();
    
        Log::debug('Setting up the Curl options array', [
            'file' => __FILE__,
            'line' => __LINE__
        ]);
        curl_setopt_array($ch, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);
    
        Log::debug('Curl session initialised successfully', [
            'file' => __FILE__,
            'line' => __LINE__
        ]);
        return $ch;
    }
}
