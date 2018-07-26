<?php

declare(strict_types = 1);
mb_internal_encoding("UTF-8");

const INPUT           = 'input';
const QUERY           = 'query';
const INPUT_TOKENIZER = 'input-tokenizer';
const QUERY_TOKENIZER = 'query-tokenizer';
const INPUT_FILTERS   = 'input-filters';
const QUERY_FILTERS   = 'query-filters';
const STEMMING        = 'stemming';
const SYNONYMS        = 'synonyms';
const SLOP            = 'slop';
const PATTERN         = 'pattern';

require_once('Validating/Validations.php');
require_once('Util/Helpers.php');
require_once('Filtering/Filters.php');
require_once('Tokenizing/Tokenizers.php');
require_once('Util/Processing.php');
require_once('Searching/Search.php');


/**
 *
 */
function main()
{
    //imports the configs used for filters
    $config = require("Filtering/config.php");

    $options = readValidatedOptionsFromTerminal(LONG_OPTS);
    //keep this value to be able to parse the text in its un-filtered/un-tokenized form for good user interaction
    $intactParagraphs = removeEmptyLines(file($options[INPUT]));

    $paragraphs = processParagraphs($intactParagraphs, $options, $config);

    $query = processQuery($options, $config);
    echo "The query is: \"" . $options[QUERY] . "\"" . PHP_EOL;

    $result = performSearch($query, $paragraphs, $intactParagraphs, (int)$options[SLOP], $options);
    //if array does not contain false there will be no trigger for the "Finished" echo
    array_push($result, false);

    showResult($result);

}


//main();

function test()
{
    $library = function (string $myString) {
        echo "Salut there" . $myString;
    };
    iDoManyThings(function (string $myString) {
        echo "Salut there" . $myString;
    });

}

function iDoManyThings(callable $executor)
{
    $c = [1, 2, 3];
    foreach ($c as $a) {
        $executor($a);
        $executor($a);
    }
}

test();