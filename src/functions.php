<?php

namespace Wordle;

/**
 * Takes a string `$word` and fills it to `$length` from the set of given `$trigrams`.
 *
 * If data is sparse and `fill_word` cannot find a continuation, the word will be returned as-is at that point.
 *
 * @param string    $word     Beginning of the word.
 * @param int       $length   Desired length of resulting string. If this is shorter than $word, $word is returned.
 * @param array[][] $trigrams Array in the form of `[two characters => [following character => frequency]`
 *
 * Example:
 * ```php
 * [
 *     "TH" => [
 *         "E" => 69221160871,
 *         "A" => 9447439870,
 *         "I" => 6357454845,
 *         "O" => 3369505315,
 *         "R" => 1673179164,
 *         ...
 *     ],
 *     "AN" => [
 *         "D" => "26468697834",
 *         "T" => "3755591976",
 *         "C" => "3061152975",
 *         ...
 *     ],
 *     ...
 * ]
 * ```
 * @param callable $rand (Optional) Callable that takes an array as a parameter and returns a key from it. Defaults to
 * `\Wordle\array_weighted_rand`
 *
 * @return string Completed word.
 */
function fill_word($word, $length, $trigrams, callable $rand = null)
{
    $rand = $rand ?: '\Wordle\array_weighted_rand';
    while (strlen($word) < $length) {
        $tail = substr($word, -2) ?: $word;
        if (!isset($trigrams[$tail])) {
            return $word;
        }
        $word .= $rand($trigrams[$tail]);
    }
    return $word;
}

/**
 * Picks a weighted random entry out of the given array and returns its key.
 *
 * For example, given the array:
 * ```
 * [
 *     'foo' => 2,
 *     'bar' => 4,
 *     'baz' => 12
 * ]
 * ```
 * It'll return `bar` about twice as often as it'll return `foo`, and `baz` about three times as often as `bar`.
 *
 * @param int[] $list Array in the form of `[key => weight]`.
 *
 * All weights **must** be whole numbers greater than or equal to zero. Total weight must exceed zero.
 *
 * @throws \InvalidArgumentException if a weight is negative or the total weight is not greater than zero.
 *
 * @return int|string The chosen key.
 */
function array_weighted_rand(array $list)
{
    $totalWeight = gmp_init(0);
    foreach ($list as $key => $weight) {
        if ($weight < 0) {
            throw new \InvalidArgumentException("Weights cannot be negative. Found $key => $weight.");
        }
        $totalWeight += $weight;
    }

    if ($totalWeight == 0) {
        throw new \InvalidArgumentException("Total weight must exceed zero.");
    } elseif ($totalWeight == 1) {
        return array_search(1, $list);
    }

    $rand = gmp_random_range(1, $totalWeight);
    foreach ($list as $key => $weight) {
        $rand -= $weight;
        if ($rand <= 0) {
            return $key;
        }
    }
}
