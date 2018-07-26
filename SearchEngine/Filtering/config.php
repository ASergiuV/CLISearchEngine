<?php

const LOWERCASE_IMPLEMENTATION = 'lowercase_implementation';
const NORMALIZE_IMPLEMENTATION = 'normalize_implementation';
const SYNONYMS_IMPLEMENTATION  = 'synonyms_implementation';
const STEMMING_IMPLEMENTATION  = 'stemming_implementation';
const PATTERN_IMPLEMENTATION   = 'pattern_implementation';

return [
    LOWERCASE_IMPLEMENTATION => 'secondVersionLowercase',
    NORMALIZE_IMPLEMENTATION => 'firstVersionNormalize',
    SYNONYMS_IMPLEMENTATION => 'firstVersionSynonyms',
    STEMMING_IMPLEMENTATION => 'firstVersionStemming',
    PATTERN_IMPLEMENTATION => 'secondVersionPattern'
];


