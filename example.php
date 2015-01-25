<?php
require_once __DIR__ . '/vendor/autoload.php';

$lengths  = json_decode(file_get_contents('data/distinct_word_lengths.json'), true);
$bigrams  = json_decode(file_get_contents('data/word_start_bigrams.json'), true);
$trigrams = json_decode(file_get_contents('data/trigrams.json'), true);


for ($i = 0; $i < 10; $i++) {
    do {
        $length = \Wordle\array_weighted_rand($lengths);
        $start  = \Wordle\array_weighted_rand($bigrams);
        $word   = \Wordle\fill_word($start, $length, $trigrams);
    } while (!preg_match('/[AEIOUY]/', $word));

    $word = strtolower($word);
    echo "$word\n";
}
