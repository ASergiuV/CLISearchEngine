<?php
/**
 * Created by PhpStorm.
 * User: sergiuabrudean
 * Date: 17.07.2018
 * Time: 14:35
 */

/**
 * @param array $config
 * @param string $pattern
 * @return callable
 */
function createPatternFilter(array $config, string $pattern): callable
{
    return function ($token) use ($config, $pattern) {

        return call_user_func($config[PATTERN_IMPLEMENTATION], $token, $pattern);
    };
}

/**
 * @param string $token
 * @param string $pattern
 * @return string
 */
function firstVersionPattern(string $token, string $pattern): array
{
    return preg_split($pattern, $token);
}

function secondVersionPattern(string $token, string $pattern): string
{
    $resultString   = [];
    $tokensExploded = explode("|", $token);

    foreach ($tokensExploded as $expToken) {
        $result = preg_match_all('/^' . $pattern . '$/u', $expToken, $matches);

        if ($result === 0) {

            return $token;
        }
        array_push($resultString, implode("|", flattenArray($matches)));
    }

    return implode("|", $resultString);
}