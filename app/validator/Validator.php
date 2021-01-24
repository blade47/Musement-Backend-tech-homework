<?php

namespace App\Validator;

/**
 * Validator used to validate custom rules on input
 *
 * @author Alessandro Afloarei <alessandro.afloarei@gmail.com>
 *
 */
class Validator
{
    /**
     * Apply the given array of rules to the given input.
     *
     * @param   array   $input      Input array
     * @param   array   $rules      Input rules
     *
     * @return  bool    True if all the rules are respected from the given input
     */
    public static function validate(array $input, array $rules)
    {
        if (is_array($input) && is_array($rules)) {
            $valid = static::applyDecoratorsFromInput($input, $rules);
            return $valid;
        } else {
            return false;
        }
    }

    /**
     * Apply rules to input using Decorator pattern
     *
     */
    private static function applyDecoratorsFromInput(array $input, array $rules)
    {
        $res = true;
        foreach ($rules as $composed_field => $rule) {
            // Create decorator for the current rule
            $decorator = static::createRuleDecorator($rule);
            if (static::isValidDecorator($decorator)) {
                $res = $res && $decorator::validate($input, $composed_field);
            }
        }
        return $res;
    }

    private static function createRuleDecorator($name)
    {
        // Build the classpath for the current rule
        return __NAMESPACE__ . '\\rules\\' . ucwords($name);
    }

    private static function isValidDecorator($decorator)
    {
        // Check if is a valid class
        return class_exists($decorator);
    }
}
