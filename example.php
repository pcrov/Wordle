<?php
require_once __DIR__ . '/src/functions.php';

$lengths  = json_decode(file_get_contents('data/distinct_word_lengths.json'), true);
$bigrams  = json_decode(file_get_contents('data/word_start_bigrams.json'), true);
$trigrams = json_decode(file_get_contents('data/trigrams.json'), true);

do {
    $length = array_weighted_rand($lengths);
    $start  = array_weighted_rand($bigrams);
    $word = fill_word($start, $length, $trigrams);
} while (!preg_match('/[AEIOUY]/', $word));

$word = strtolower($word);
echo $word;
