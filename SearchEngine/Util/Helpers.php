<?php
/**
 * Created by PhpStorm.
 * User: sergiuabrudean
 * Date: 16.07.2018
 * Time: 13:59
 */

const REGEX_VERIFIER = '/^((?:(?:[^?+*{}()[\]\\\\|]+|\\\\.|\[(?:\^?\\\\.|\^[^\\\\]|[^\\\\^])(?:[^\]\\\\]+|\\\\.)*\]|\((?:\?[:=!]|\?<[=!]|\?>)?(?1)??\)|\(\?(?:R|[+-]?\d+)\))(?:(?:[?+*]|\{\d+(?:,\d*)?\})[?+]?)?|\|)*)$/';

const LONG_OPTS = [
    "input:",
    "query:",
    "input-tokenizer:",
    "query-tokenizer:",
    "input-filters:",
    "query-filters:",
    "pattern:",
    "synonyms:",
    "stemming:",
    "slop:",
    "synonyms:",
];

/**
 * @param array $longOpts
 * @return array
 */
function readOptionsFromTerminal(array $longOpts): array
{
    $options1 = getopt("", $longOpts, $optind);

    foreach ($longOpts as $key => $val) {
        $options[rtrim($val, ":")] = null;
    }

    $options[SLOP] = 0;
    //merge arrays overwriting the default null values
    $options2 = $options1 + $options;

    return $options2;
}

/**
 * @param array $longOpts
 * @return array
 */
function readValidatedOptionsFromTerminal(array $longOpts): array
{
    $options = readOptionsFromTerminal($longOpts);

    validateTerminalOptions($options);

    return $options;
}

/**
 * @param string $fileName
 * @return array
 */
function readLinesCSV(string $fileName): array
{

    $lines  = array();
    $handle = fopen($fileName, "r");

    if ($handle != false) {
        while (($line = fgetcsv($handle)) !== false) {
            array_push($lines, $line);
        }

        fclose($handle);

        return $lines;
    }
    return [];
}

/**
 * @param array $result
 */
function showResult(array $result)
{
    foreach ($result as $res) {
        if ($res === false) {
            echo "Finished!" . PHP_EOL . PHP_EOL;
            break;
        }
        if (isset($res[0][0][0])) {
            echo "The paragraph index is " . ($res[1] + 1) . PHP_EOL . "The quote: \"" . $res[0][0][0] . "\"" . PHP_EOL;
        }

    }
}

/**
 * Returns a one-dimensional array from an array of any depth
 *
 * @param $tokens
 * @return array
 */
function flattenArray($tokens)
{
    $flattenedToken = array();
    $count          = 0;
    $it             = new RecursiveIteratorIterator(new RecursiveArrayIterator($tokens));

    foreach ($it as $v) {
        $flattenedToken[$count] = $v;
        $count++;
        $tokens = $flattenedToken;
    }

    return $tokens;
}

/**
 * checks if a pattern is valid
 *
 * @param $str
 * @return bool
 */
function isRegularExpression($str): bool
{
    $test = preg_match(REGEX_VERIFIER, $str, $matches, PREG_OFFSET_CAPTURE, 0);

    if ($test === 0) {

        return false;
    }

    return true;
}

/**
 * @param array $arr
 * @return array
 */
function reduceAndSort(array $arr): array
{
    $arr = flattenArray($arr);
    $arr = array_unique($arr);
    foreach ($arr as $k => $v) {
        if ($v === false) {
            $arr[$k] = -1;
        }
        if ($v === true) {
            unset($arr[$k]);
        }
    }
    $arr = array_values($arr);
    sort($arr);

    return $arr;
}


/**
 * Print array to screen
 *
 * @param array $Array
 */
function displayArray2d(array $Array)
{

    echo PHP_EOL . PHP_EOL;
    foreach ($Array as $paragraph) {
        foreach ($paragraph as $key => $line) {
            echo $key . "   " . $line . PHP_EOL;
        }
    }
    echo PHP_EOL . PHP_EOL;
}

/**
 * Removes lines which only contain \n and leaves the indexes unchanged
 *
 * @param array $fileArray
 * @return array
 *
 */
function removeEmptyLines(array $fileArray): array
{
    foreach ($fileArray as $key => $line) {
        if ($line === PHP_EOL) {
            unset($fileArray[$key]);
        }
    }

    return $fileArray;
}

/**
 * @param string $splitAndTrim
 * @return array
 */
function splitAtCommaAndTrimDash(string $splitAndTrim): array
{
    $result = explode(",", $splitAndTrim);

    foreach ($result as $key => $value) {
        $result[$key] = rtrim($value, "-");
    }

    return $result;
}