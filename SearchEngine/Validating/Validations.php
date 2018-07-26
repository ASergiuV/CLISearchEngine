<?php
/**
 * Created by PhpStorm.
 * User: sergiuabrudean
 * Date: 16.07.2018
 * Time: 13:13
 * @param array $options
 * @return bool
 */


/**
 * @param array $options
 * @return array
 */
function inputValidation(array $options): array
{
    $errorArray = [true];
    if (empty($options[INPUT])) {
        array_push($errorArray, "Input field must not be empty!");
        $errorArray[0] = false;

        return $errorArray;
    }
    if (!file_exists($options[INPUT])) {
        array_push($errorArray, "Input file not found!");
        $errorArray[0] = false;

        return $errorArray;
    }
    if (mime_content_type($options[INPUT]) !== "text/plain") {
        array_push($errorArray, "Input file must be a .txt!");
        $errorArray[0] = false;
    }

    return $errorArray;
}

/**
 * @param array $options
 * @return array
 */
function queryValidation(array $options): array
{
    $errorArray = [true];
    if (empty($options[QUERY])) {
        array_push($errorArray, "Query must not be empty!");
        $errorArray[0] = false;
    }

    return $errorArray;
}

/**
 * @param array $options
 * @return array
 */
function inputTokenizerValidation(array $options): array
{
    $errorArray = [true];
    if (empty($options[INPUT_TOKENIZER])) {
        array_push($errorArray, "Input-tokenizer can't be left empty!");
        $errorArray[0] = false;
    }
    if ($options[INPUT_TOKENIZER] !== "whitespace" &&
        $options[INPUT_TOKENIZER] !== "standard") {
        array_push($errorArray, "Input-tokenizer must be whitespace or standard!");
        $errorArray[0] = false;
    }

    return $errorArray;
}

/**
 * @param array $options
 * @return array
 */
function queryTokenizerValidation(array $options): array
{
    $errorArray = [true];
    if (empty($options[QUERY_TOKENIZER])) {
        array_push($errorArray, "Query-tokenizer can't be left empty!");
        $errorArray[0] = false;
    }
    if ($options[QUERY_TOKENIZER] !== "whitespace" &&
        $options[QUERY_TOKENIZER] !== "standard") {
        array_push($errorArray, "Query-tokenizer must be whitespace or standard!");
        $errorArray[0] = false;
    }

    return $errorArray;
}

/**
 * @param array $options
 * @return array
 */
function inputFilterValidation(array $options): array
{

    $errorArray = [true];

    if ($options[INPUT_FILTERS] === null) {

        return $errorArray;
    }

    if (empty($options[INPUT_FILTERS])) {
        array_push($errorArray, mb_strtoupper("Input filters list is empty!"));
        $errorArray[0] = false;
    }

    $queryFilters = explode(",", $options[INPUT_FILTERS]);
    if (empty($queryFilters) || count($queryFilters) > 5) {
        array_push($errorArray, 'input-filters must be one or more of the following:
        lowercase, normalize, stemming, pattern-capture or synonyms');
        $errorArray[0] = false;
    }

    foreach ($queryFilters as $filter) {
        if ($filter !== "lowercase" && $filter !== "normalize" && $filter !== "synonyms" &&
            $filter !== "pattern-capture" && $filter !== "stemming") {
            array_push($errorArray, 'input-filters must be one or more of the following:
            lowercase, normalize, stemming, pattern-capture or synonyms');
            $errorArray[0] = false;
        }
    }

    if (in_array(SYNONYMS, $queryFilters) && $options[SYNONYMS] === null) {
        array_push($errorArray, mb_strtoupper("synonyms keyword present in input-filters but not set!"));
        $errorArray[0] = false;
    }
    if (in_array("pattern-capture", $queryFilters) && $options[PATTERN] === null) {
        array_push($errorArray, mb_strtoupper("pattern-capture keyword present in input-filters but not set!"));
        $errorArray[0] = false;
    }
    if (in_array(STEMMING, $queryFilters) && $options[STEMMING] === null) {
        array_push($errorArray, mb_strtoupper("stemming keyword present in input-filters but not set!"));
        $errorArray[0] = false;
    }

    return $errorArray;
}

/**
 * @param array $options
 * @return array
 */
function queryFiltersValidation(array $options): array
{
    $errorArray = [true];

    if ($options[QUERY_FILTERS] === null) {

        return $errorArray;
    }

    if (empty($options[QUERY_FILTERS])) {
        array_push($errorArray, mb_strtoupper("Query filters list is empty!"));
        $errorArray[0] = false;
    }

    $queryFilters = explode(",", $options[QUERY_FILTERS]);
    if (empty($queryFilters) || count($queryFilters) > 4) {
        array_push($errorArray, 'query-filters must be one or more of the following:
        lowercase, normalize, stemming, pattern-capture or synonyms');
        $errorArray[0] = false;
    }

    foreach ($queryFilters as $filter) {
        if ($filter !== "lowercase" && $filter !== "normalize" && $filter !== "synonyms" &&
            $filter !== "pattern-capture" && $filter !== "stemming") {
            array_push($errorArray, 'query-filters must be one or more of the following:
            lowercase, normalize, stemming, pattern-capture or synonyms');
            $errorArray[0] = false;
        }
    }

    if (in_array(SYNONYMS, $queryFilters) && $options[SYNONYMS] === null) {
        array_push($errorArray, mb_strtoupper("synonyms keyword present in query-filters but not set!"));
        $errorArray[0] = false;
    }
    if (in_array("pattern-capture", $queryFilters) && $options[PATTERN] === null) {
        array_push($errorArray, mb_strtoupper("pattern-capture keyword present in query-filters but not set!"));
        $errorArray[0] = false;
    }
    if (in_array(STEMMING, $queryFilters) && $options[STEMMING] === null) {
        array_push($errorArray, mb_strtoupper("stemming keyword present in query-filters but not set!"));
        $errorArray[0] = false;
    }

    return $errorArray;
}

/**
 * @param array $options
 * @return array
 */
function synonymsValidation(array $options): array
{
    $errorArray = [true];
    if (null !== $options[SYNONYMS]) {
        if (file_exists($options[SYNONYMS])) {
            if (mime_content_type($options[SYNONYMS]) !== "text/plain") {
                array_push($errorArray, mb_strtoupper("synonym file must be csv!"));
                $errorArray[0] = false;
            } else {
                if (readLinesCSV($options[SYNONYMS]) === []) {
                    array_push($errorArray, mb_strtoupper("synonym file is invalid!"));
                    $errorArray[0] = false;
                }
            }
        } else {
            array_push($errorArray, mb_strtoupper("synonym file does not exist!"));
            $errorArray[0] = false;
        }
    }

    return $errorArray;

}

/**
 * @param array $options
 * @return array
 */
function stemmingValidation(array $options): array
{
    $errorArray = [true];
    if (strpos($options[INPUT_FILTERS], STEMMING) === false &&
        strpos($options[QUERY_FILTERS], STEMMING) === false) {

        return $errorArray;
    }
    if (empty($options[STEMMING])) {
        array_push($errorArray, mb_strtoupper("stemming array is empty!"));
        $errorArray[0] = false;
    }

    return $errorArray;
}

/**
 * @param array $options
 * @return array
 */
function slopValidation(array $options): array
{
    $errorArray = [true];

    if ($options[SLOP] === null && !is_numeric($options[SLOP])) {
        array_push($errorArray, sprintf("Slop is not numeric. Slop is %s", $options['slop']));
        $errorArray[0] = false;
    }

    return $errorArray;
}

/**
 * @param array $options
 * @return array
 */
function patternValidation(array $options): array
{
    $errorArray = [true];
    if (strpos($options[INPUT_FILTERS], 'pattern-capture') === false &&
        strpos($options[QUERY_FILTERS], 'pattern-capture') === false) {

        return $errorArray;
    }
    if (empty($options[PATTERN])) {
        array_push($errorArray, mb_strtoupper("Pattern must be given!"));
        $errorArray[0] = false;
    }
    if (!isRegularExpression("/" . $options[PATTERN] . "/")) {
        array_push($errorArray, mb_strtoupper("Regular expression is invalid!"));
        $errorArray[0] = false;
        array_push($errorArray, false);
    }

    return $errorArray;
}

/**
 * @param array $options
 */
function validateTerminalOptions(array $options)
{

    $errors = [
        inputValidation($options),
        inputTokenizerValidation($options),
        queryTokenizerValidation($options),
        queryValidation($options),
        queryFiltersValidation($options),
        inputFilterValidation($options),
        synonymsValidation($options),
        stemmingValidation($options),
        slopValidation($options),
        patternValidation($options)
    ];


    $errors = reduceAndSort($errors);
    $index  = count($errors) - 1;

    echo "Current Process: " . getmypid() . PHP_EOL;

    while ($index >= 1) {
        echo sprintf("Error number %s : %s" . PHP_EOL, $index, $errors[$index--]);
    }

    if ((isset($errors[0]) && $errors[0] === -1)) {
        echo PHP_EOL;
        die;
    }

    echo PHP_EOL . strtoupper("\tReading from terminal was successful!") . PHP_EOL;

}







