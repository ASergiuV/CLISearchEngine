<?php
/**
 * Created by PhpStorm.
 * User: sergiuabrudean
 * Date: 17.07.2018
 * Time: 12:06
 */

require_once('TokenizerFactory.php');


/**
 * @param array $paragraphs
 * @param string $tokenizeType
 * @return array
 */
function applyTokenizerForInput(array $paragraphs, string $tokenizeType): array
{

    $tokenizer = null;
    switch ($tokenizeType) {
        case 'whitespace':
            $tokenizer = createWhitespaceTokenizer();
            break;
        case 'standard':
            $tokenizer = createStandardTokenizer();
            break;
    }

    if ($tokenizer == null) {

        return $paragraphs;
    }

    foreach ($paragraphs as $paragraphKey => $paragraph) {
        $paragraphs[$paragraphKey] = tokenizeParagraph($paragraph, $tokenizer);
    }

    return $paragraphs;
}

/**
 * @param string $query
 * @param string $tokenizeType
 * @return array
 */
function applyTokenizerForQuery(string $query, string $tokenizeType): array
{

    $tokenizer = null;
    switch ($tokenizeType) {
        case 'whitespace':
            $tokenizer = createWhitespaceTokenizer();
            break;
        case 'standard':
            $tokenizer = createStandardTokenizer();
            break;
    }

    $query = tokenizeParagraph($query, $tokenizer);

    return $query;
}

/**
 * Applies a token filter over a token string.
 *
 * @param string $paragraph
 * @param callable $tokenizer
 * @return array
 */
function tokenizeParagraph(string $paragraph, callable $tokenizer): array
{
    return $tokenizer($paragraph);
}