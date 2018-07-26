<?php
/**
 * Created by PhpStorm.
 * User: sergiuabrudean
 * Date: 17.07.2018
 * Time: 18:02
 * @param array $paragraphs
 * @param array $options
 * @param array $config
 * @return array
 */


/**
 * @param array $paragraphs
 * @param array $options
 * @param array $config
 * @return array
 */
function processParagraphs(array $paragraphs, array $options, array $config): array
{
    $paragraphs = applyTokenizerForInput($paragraphs, $options[INPUT_TOKENIZER]);
    $paragraphs = applyInputFilters($paragraphs, $options, $config);

    return $paragraphs;
}

/**
 * @param array $options
 * @param array $config
 * @return array
 */
function processQuery(array $options, array $config)
{
    $queryArray = applyTokenizerForQuery($options[QUERY], $options[QUERY_TOKENIZER]);
    $queryArray = applyQueryFilters($queryArray, $options, $config);

    return $queryArray;
}
