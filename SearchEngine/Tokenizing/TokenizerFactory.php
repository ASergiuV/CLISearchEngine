<?php
/**
 * Created by PhpStorm.
 * User: sergiuabrudean
 * Date: 16.07.2018
 * Time: 15:17
 */


const WHITESPACE_PATTERN = "/\s+/";
const STANDARD_PATTERN   = "/\b[\p{L}\d\-]+\b/u";
const PATTERNS           = array(
    '/\s|(?<=\w)(?=[.,:;!?)])|(?<=[.,"!()?\x{201C}])/u',
    '/\s+|[^\W]+|/',
    '/[\s\p{Po}\p{Z}]+/',
    '/[\p{Han}\p{wd=SContinue}\p{wd=Hiragana}\p{wd=Katakana}\p{wd=Extend}\p{wd=ALetter}\p{wd=MidLetter}\p{wd=MidNumLet}.,:;!?()\s]|(?<=\s)-(?=\s)/u',
    '/[.,\/#!$%\^&\*;:{}=\-_`~()]+\s+/u',
    "/[.,:;!?()\s]|(?<=\s)[\-'](?=\s)/u"
);


/**
 * Factory
 *
 * @return Closure
 */
function createWhitespaceTokenizer() //ce return type are call user func
{
    return function ($paragraph) {

        return call_user_func('firstWhitespaceImplementation', $paragraph);
    };
}

/**
 * Factory
 *
 * @return Closure
 */
function createStandardTokenizer()
{
    return function ($paragraph) {

        return call_user_func('secondStandardImplementation', $paragraph);
    };
}

//Various Tokenizer Implementations
/**
 * @param string $paragraph
 * @return array
 */
function firstWhitespaceImplementation(string $paragraph): array
{
    return preg_split(WHITESPACE_PATTERN, $paragraph, -1, PREG_SPLIT_NO_EMPTY);
}

/**
 * @param string $paragraph
 * @return array
 */
function secondStandardImplementation(string $paragraph): array
{
    preg_match_all(STANDARD_PATTERN, $paragraph, $matches, PREG_SPLIT_NO_EMPTY);
    $result = flattenArray($matches);

    return $result;
}

/**
 * @param string $paragraph
 * @return array
 */
function firstStandardImplementation(string $paragraph): array
{
    return preg_split(PATTERNS[4], $paragraph, -1, PREG_SPLIT_NO_EMPTY);
}