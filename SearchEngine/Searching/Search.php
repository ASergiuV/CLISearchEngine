<?php
/**
 * Created by PhpStorm.
 * User: sergiuabrudean
 * Date: 18.07.2018
 * Time: 12:58
 */


const SEARCH_PATTERN_1 = '/(?:\S+\s){0,3}\S*\b(';
const SEARCH_PATTERN_2 = ')(.)*(';
const SEARCH_PATTERN_3 = ')\b\S*(?:\s\S+){0,3}/u';

const SEARCH_PATTERN_4 = '/(?:\S+\s){0,3}\S*\b(';
const SEARCH_PATTERN_5 = ')\b\S*(?:\s\S+){0,3}/u';

/**
 * @param array $query
 * @param array $paragraph
 * @param int $slop
 * @return bool|array
 */
function indexSearch(array $query, array $paragraph, int $slop)
{
    $firstAndLastIndex = array();
    $index             = 0;
    $slop++;
    $paragraphLength = count($paragraph);
    $queryLength     = count($query);
    $maxSearchIndex  = $paragraphLength - $queryLength;

    while ($index <= $maxSearchIndex) {
        $distance       = 0;
        $paragraphIndex = $index;
        $queryIndex     = 0;

        if (preg_match("/\b($query[$queryIndex])\b/u", implode(" ", explode("|", $paragraph[$paragraphIndex]))) < 1) {
            $index++;
            continue;
        }

        while ($paragraphIndex < $paragraphLength && $queryIndex < $queryLength && $distance < $slop) {
            if (preg_match("/\b($query[$queryIndex])\b/u",
                    implode(" ", explode("|", $paragraph[$paragraphIndex]))) >= 1) {
                //reset distance counter because of slop
                $distance = 0;
                array_push($firstAndLastIndex, $paragraphIndex);
                $paragraphIndex++;
                $queryIndex++;
                continue;
            }
            $paragraphIndex++;
            $distance++;
        }
        if ($queryIndex === count($query)) {

            return array($firstAndLastIndex[0], $firstAndLastIndex[count($firstAndLastIndex) - 1]);
        }
        $index++;
    }
    if ($index >= $maxSearchIndex) {
        //End of paragraph
        return false;
    }

    return array($firstAndLastIndex[0], $firstAndLastIndex[count($firstAndLastIndex) - 1]);
}

/**
 * Parse paragraphs, call indexSearch on each paragraph and for the first found query return paragraph index
 * and first matched word to be used in regex to find in intactParagraphs the initial text
 *
 * @param array $query
 * @param array $paragraphs
 * @param int $slop
 * @return array
 */
function search(array $query, array $paragraphs, int $slop)
{
    foreach ($paragraphs as $key => $paragraph) {
        $searchResult = indexSearch($query, $paragraph, $slop);

        if ($searchResult !== false) {
            return array(
                'firstTokenIndex' => $searchResult[0],
                'lastTokenIndex' => $searchResult[1],
                'paragraphIndex' => $key,
                'firstMatchedWord' => $paragraph[$searchResult[0]]
            );
        }

    }
    return array();
}


/**
 * Create search pattern from firstFoundWord from the query and add distance to get context
 *
 * @param string $firstMatchToken
 * @param string $lastMatchToken
 * @param int $queryCount
 * @param int $slop
 * @return string
 */
function querySearchPattern(string $firstMatchToken, string $lastMatchToken, int $queryCount, int $slop): string
{
    if ($queryCount === 1) {
        return SEARCH_PATTERN_4 . $firstMatchToken . SEARCH_PATTERN_5;
    }
    return SEARCH_PATTERN_1 . $firstMatchToken . SEARCH_PATTERN_2 . $lastMatchToken . SEARCH_PATTERN_3;
}

/**
 * @param array $query
 * @param array $paragraphs
 * @param array $intactParagraphs
 * @param int $slop
 * @param array $options
 * @return bool|array
 */
function performSearch(array $query, array $paragraphs, array $intactParagraphs, int $slop, array $options)
{
    $resultArray         = [];
    $searchResult        = search($query, $paragraphs, $slop);
    $paragraphsTokenized = applyTokenizerForInput($intactParagraphs, $options[INPUT_TOKENIZER]);
    while ($searchResult !== []) {
        $firstTextWord = $paragraphsTokenized[$searchResult['paragraphIndex']][$searchResult['firstTokenIndex']];
        $lastTextWord  = $paragraphsTokenized[$searchResult['paragraphIndex']][$searchResult['lastTokenIndex']];

        $re = querySearchPattern($firstTextWord, $lastTextWord, count($query), $slop);

        if (1 < preg_match($re, $intactParagraphs[$searchResult['paragraphIndex']], $matches,
                PREG_OFFSET_CAPTURE, 0)) {
            unset($paragraphs[$searchResult['paragraphIndex']]);

            continue;
        }

        array_push($resultArray, [$matches, $searchResult['paragraphIndex']]);

        foreach ($paragraphs as $k => $v) {
            if ($k <= $searchResult['paragraphIndex']) {
                unset($paragraphs[$k]);
            }
        }
        $searchResult = search($query, $paragraphs, $slop);
    };

    return $resultArray;
}