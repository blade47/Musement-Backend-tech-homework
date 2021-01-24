<?php

namespace App\Validator\Rules;

/**
 * Rule interface
 *
 * @author Alessandro Afloarei <alessandro.afloarei@gmail.com>
 *
 */
interface Rule
{
    /**
     * Validate a given field value in the input instance.
     *
     *  Example of input:
     *      array(
     *          'forecast' =>
     *          array(
     *              'forecastday' =>
     *              array(
     *                  0 =>
     *                  array(
     *                      'date' => '2021-01-24'
     *                  )
     *              ),
     *          ),
     *      )
     *
     *  Example of composed field:
     *      'forecast.forecastday.date'
     *
     *
     * @param   array   $input              Input array
     * @param   string  $composed_field     Composed field to be validated
     *
     * @return  bool    True if all the composed field respects the custom rule, false otherwise
     */
    public static function validate(array $input, $composed_field);
}
