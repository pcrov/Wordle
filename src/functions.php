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

    $rand = gmp_rand(1, $total_weight);
    foreach ($list as $key => $weight) {
        $rand -= $weight;
        if ($rand <= 0) return $key;
    }
}

/**
 * @param $min
 * @param $max
 *
 * @return resource
 */
function gmp_rand($min, $max) {
    $max -= $min;
    $bit_length = strlen(gmp_strval($max, 2));

    do {
        $rand = gmp_init(0);
        for ($i = $bit_length - 1; $i >= 0; $i--) {
            gmp_setbit($rand, $i, rand(0, 1));
            if ($rand > $max) break;
        }
    } while ($rand > $max);

    return $rand + $min;
}