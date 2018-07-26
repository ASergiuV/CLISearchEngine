<?php
/**
 * Created by PhpStorm.
 * User: sergiuabrudean
 * Date: 17.07.2018
 * Time: 14:29
 */


/**
 * Factory function
 *
 * @param array $config
 * @return Closure
 */
function createLowercaseFilter(array $config): callable
{
    return function ($token) use ($config) {

        return call_user_func($config[LOWERCASE_IMPLEMENTATION], $token);
    };
}

// Various Lowercase implementations
/**
 * @param $token
 * @return string
 */
function firstVersionLowercase(string $token): string
{
    return strtolower($token);
}

/**
 * @param $token
 * @return string
 */
function secondVersionLowercase(string $token): string
{
    return mb_strtolower($token);
}