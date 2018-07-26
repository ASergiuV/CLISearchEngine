<?php
/**
 * Created by PhpStorm.
 * User: sergiuabrudean
 * Date: 17.07.2018
 * Time: 14:31
 */


/**
 * @param array $config
 * @param array $synonyms
 * @return callable
 */
function createSynonymsFilter(array $config, array $synonyms): callable
{
    return function ($token) use ($config, $synonyms) {

        return call_user_func($config[SYNONYMS_IMPLEMENTATION], $token, $synonyms);
    };
}

/**
 * @param string $token
 * @param array $synonyms
 * @return string
 */
function firstVersionSynonyms(string $token, array $synonyms): string
{
    $replacementLine = false;
    foreach ($synonyms as $synonymLine) {
        foreach ($synonymLine as $word) {
            if (strcmp($word, $token) === 0) {
                $replacementLine = $synonymLine;
                break 2;
            }
        }

    }
    if (empty($replacementLine)) {

        return $token;
    }
    $replacementString = implode($replacementLine, "|");

    return $replacementString;
}
