<?php
/**
 * Created by PhpStorm.
 * User: sergiuabrudean
 * Date: 17.07.2018
 * Time: 14:31
 */


/**
 * @param array $config
 * @param string $suffixes
 * @return callable
 */
function createStemmingFilter(array $config, string $suffixes): callable
{
    return function ($token) use ($config, $suffixes) {
        $suffixes = splitAtCommaAndTrimDash($suffixes);

        return call_user_func($config[STEMMING_IMPLEMENTATION], $token, $suffixes);
    };
}

/**
 * @param string $initialToken
 * @param array $suffixes
 * @return string
 */
function firstVersionStemming(string $initialToken, array $suffixes): string
{
    $resultToken = [];

    foreach (explode("|", $initialToken) as $token) {
        $tokenLength = strlen($token);

        foreach ($suffixes as $suffix) {
            $suffixLength = strlen($suffix);

            if ($tokenLength - $suffixLength < 3) {
                continue;
            }

            if (substr_compare($token, $suffix, $tokenLength - $suffixLength) === 0) {
                array_push($resultToken, substr($token, 0, $tokenLength - $suffixLength));
            }
        }
        array_push($resultToken, $token);
    }

    return implode("|", $resultToken);
}