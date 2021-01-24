<?php

namespace App\Validator\Rules;

/**
 * Required rule.
 * Only checks if the composed field is setted in the given array input
 *
 * @author Alessandro Afloarei <alessandro.afloarei@gmail.com>
 *
 */
class Required implements Rule
{
    public static function validate(array $input, $composed_field)
    {
        $indexes = explode('.', $composed_field);
        return static::deepValidation($input, $indexes);
    }

    /**
     * Perform a deep validation on the input using the composed field
     *
     * @param   array   $input      Input array
     * @param   array   $indexes    Array of Strings given by the explosion
     *                              of the initial composed field
     * @return  bool    True if all the composed field is setted, false otherwise
     */
    private static function deepValidation(array $input, array $indexes)
    {
        // Count the number of indexes
        $count = count($indexes);
    
        // Use temp to go deep into the input array
        $temp = $input;
    
        $res = true;
        for ($i = 0; $i < $count; $i++) {
            // Check the array at the current index
            if (!isset($temp[$indexes[$i]])) {
                $res = false;
                break;
            }
            $temp = $temp[$indexes[$i]];
        }
    
        return $res;
    }
}
