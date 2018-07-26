#!/usr/bin/env bash
#works, not is synonymized to nema and Lorem is also synonymized to nema
php SearchEngine.php --input input_file.txt --query 'not ípsum' --input-tokenizer standard --query-tokenizer standard --input-filters synonyms --query-filters synonyms --synonyms file.csv

#finds Boeing727 when given Boeing
php SearchEngine.php --input input_file.txt --query 'Boeing' --input-tokenizer standard --query-tokenizer whitespace --input-filters synonyms,stemming,pattern-capture,lowercase,normalize --query-filters normalize,lowercase --synonyms file.csv --stemming nd --pattern '([a-zA-Z]+)([0-9]+)' --slop 20

#finds Boeing727 when given Boeing but doesnt find the 2nd paragraph in which Boeing727 exists because it is preceded by "." and the tokenization is whitespace not standard
php SearchEngine.php --input input_file.txt --query 'Boeing' --input-tokenizer whitespace --query-tokenizer whitespace --input-filters synonyms,stemming,pattern-capture,lowercase,normalize --query-filters normalize,lowercase --synonyms file.csv --stemming nd --pattern '([a-zA-Z]+)([0-9]+)' --slop 20

#doesn not find Boeing727 when given Boeing because only 1 filter is lowercase
php SearchEngine.php --input input_file.txt --query 'Boeing' --input-tokenizer standard --query-tokenizer whitespace --input-filters synonyms,stemming,pattern-capture,lowercase,normalize --query-filters normalize --synonyms file.csv --stemming nd --pattern '([a-zA-Z]+)([0-9]+)' --slop 20

#finds Boeing727 when given 727 unaffected by lowercase difference
php SearchEngine.php --input input_file.txt --query '727' --input-tokenizer standard --query-tokenizer whitespace --input-filters synonyms,stemming,pattern-capture,lowercase,normalize --query-filters normalize --synonyms file.csv --stemming nd --pattern '([a-zA-Z]+)([0-9]+)' --slop 20

#input error because php is passed
php SearchEngine.php --input SearchEngine.php --query 'if' --input-tokenizer standard --query-tokenizer standard --input-filters normalize --query-filters normalize

#wrong input, empty query, incorectly written words, wrong file type for synonyms, nonnumeric slop
php SearchEngine.php --input das --query '' --input-tokenizer standar --query-tokenizer whitespac --input-filters synonym,stemming,pattern-captur,lowercas,normalize --query-filters normalize,lowercase --synonyms SearchEngine.php --stemming nd  --slop gfds

#every field empty
php SearchEngine.php --input '' --query '' --input-tokenizer '' --query-tokenizer '' --input-filters '' --query-filters '' --synonyms '' --stemming ''  --slop ''

#wrong pattern
php SearchEngine.php --input input_file.txt --query 'Nó vělit peřtinačía' --input-tokenizer standard --query-tokenizer whitespace --input-filters synonyms,stemming,pattern-capture,lowercase,normalize --query-filters normalize,lowercase --synonyms file.csv --stemming nd,em,li --pattern '([a-zA-Z]+)(' --slop 20

#works peřtinačía is at a distance of 15-20 words after vělit
php SearchEngine.php --input input_file.txt --query 'Nó vělit peřtinačía' --input-tokenizer standard --query-tokenizer whitespace --input-filters stemming,lowercase,normalize --query-filters normalize,lowercase --synonyms file.csv --stemming nd,em,li --slop 20

#works
php SearchEngine.php --input input_file.txt --query 'Nó vělit peřtinačía' --input-tokenizer standard --query-tokenizer whitespace --input-filters lowercase,normalize --query-filters normalize,lowercase --synonyms file.csv --stemming nd,em,li --slop 20

#words too far apart , paragraph 9 for normal people, 8 for programmers
php SearchEngine.php --input input_file.txt --query 'Nó peřtinačía' --input-tokenizer standard --query-tokenizer standard --input-filters lowercase,normalize --query-filters normalize,lowercase --synonyms file.csv --stemming nd,em,li --slop 2

#no given slop will use slop 0, doesn't work
php SearchEngine.php --input input_file.txt --query 'Nó vělit peřtinačía' --input-tokenizer standard --query-tokenizer standard --input-filters lowercase,normalize --query-filters normalize,lowercase --synonyms file.csv --stemming nd,em,li

#only 1 normalized so it doesnt work
php SearchEngine.php --input input_file.txt --query 'Nó vělit' --input-tokenizer standard --query-tokenizer standard --input-filters lowercase --query-filters normalize,lowercase

#one normalized and the other lowercase doesn't work
php SearchEngine.php --input input_file.txt --query 'Nó vělit peřtinačía' --input-tokenizer standard --query-tokenizer standard --input-filters lowercase --query-filters normalize --slop 20

#both lowercase works
php SearchEngine.php --input input_file.txt --query 'Nó vělit' --input-tokenizer standard --query-tokenizer standard --input-filters lowercase --query-filters lowercase

#both normalized works
php SearchEngine.php --input input_file.txt --query 'Nó vělit peřtinačía' --input-tokenizer standard --query-tokenizer standard --input-filters normalize --query-filters normalize --slop 20

#synonyms used for input no|not|nema , works, finds "not sonet"
php SearchEngine.php --input input_file.txt --query 'no sonet' --input-tokenizer standard --query-tokenizer whitespace --input-filters synonyms,stemming,pattern-capture,lowercase,normalize --query-filters normalize,lowercase --synonyms file.csv --stemming nd --pattern '([a-zA-Z]+)([0-9]+)' --slop 20