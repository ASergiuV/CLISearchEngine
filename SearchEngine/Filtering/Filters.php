<?php
/**
 * Created by PhpStorm.
 * User: sergiuabrudean
 * Date: 17.07.2018
 * Time: 11:30
 */


require_once('FilterFactory.php');


/**
 * Parse all filters and call a function for all the tokens for each filter
 *
 * @param array $paragraphs
 * @param array $options
 * @param array $config
 * @return array
 */
function applyInputFilters(array $paragraphs, array $options, array $config): array
{
    foreach (explode(",", $options[INPUT_FILTERS]) as $filter) {
        $tokenFilter = null;

        switch ($filter) {
            case 'lowercase':
                $tokenFilter = createLowercaseFilter($config);
                break;
            case 'normalize':
                $tokenFilter = createNormalizeFilter($config);
                break;
            case 'stemming':
                $tokenFilter = createStemmingFilter($config, $options[STEMMING]);
                break;
            case 'pattern-capture':
                $tokenFilter = createPatternFilter($config, $options[PATTERN]);
                break;
            case 'synonyms':
                $tokenFilter = createSynonymsFilter($config, readLinesCSV($options[SYNONYMS]));
                break;
            default:
                break;
        }

        if ($tokenFilter == null) {

            return $paragraphs;
        }
        //Filter each paragraph
        foreach ($paragraphs as $k => $paragraphTokens) {
            $paragraphs[$k] = filterTokens($paragraphTokens, $tokenFilter);
        }
        //Flatten to a bi-dimensional array
        //Some filters return array of array
        foreach ($paragraphs as $key => $paragraph) {
            $paragraphs[$key] = flattenArray($paragraph);
        }
    }

    return $paragraphs;
}

/**
 * Parse all filters and call a function for all the tokens for each filter
 *
 * @param array $queryTokens
 * @param array $options
 * @param array $config
 * @return array
 */
function applyQueryFilters(array $queryTokens, array $options, array $config): array
{
    foreach (explode(",", $options[QUERY_FILTERS]) as $filter) {
        $tokenFilter = null;

        switch ($filter) {
            case 'lowercase':
                $tokenFilter = createLowercaseFilter($config);
                break;
            case 'normalize':
                $tokenFilter = createNormalizeFilter($config);
                break;
            case 'stemming':
                $tokenFilter = createStemmingFilter($config, $options[STEMMING]);
                break;
            case 'pattern-capture':
                $tokenFilter = createPatternFilter($config, $options[PATTERN]);
                break;
            case 'synonyms':
                $tokenFilter = createSynonymsFilter($config, readLinesCSV($options[SYNONYMS]));
                break;
            default:
                break;
        }

        if ($tokenFilter == null) {

            return $queryTokens;
        }

        $queryTokens = filterTokens($queryTokens, $tokenFilter);
        $queryTokens = flattenArray($queryTokens);
    }

    return $queryTokens;
}

/**
 * Applies a token filter over a token array.
 *
 * @param array $tokens
 * @param callable $tokenFilter
 * @return array
 */
function filterTokens(array $tokens, callable $tokenFilter): array
{
    foreach ($tokens as $k => $token) {
        $tokens[$k] = $tokenFilter($token);
    }

    return $tokens;
}

