<?php

/**
 * @param $word
 * @param $length
 * @param $trigrams
 *
 * @return string
 */
function fill_word ($word, $length, $trigrams) {
    while (strlen($word) < $length) {
        $word .= array_weighted_rand($trigrams[substr($word, -2)]);
    }
    return $word;
}

/**
 * @param $list
 *
 * @return int|string
 */
/** @noinspection PhpInconsistentReturnPointsInspection */
function array_weighted_rand ($list) {
    $total_weight = gmp_init(0);
    foreach ($list as $weight) {
        $total_weight += $weight;
    }

    $rand = gmp_random_range(1, $total_weight);
    foreach ($list as $key => $weight) {
        $rand -= $weight;
        if ($rand <= 0) return $key;
    }
}