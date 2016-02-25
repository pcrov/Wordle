Wordle
=======================

[![Build Status](https://travis-ci.org/pcrov/Wordle.svg?branch=master)](https://travis-ci.org/pcrov/Wordle)

*Why the name "Wordle"? Because that's what it generated for itself when I fed it "word" as a starting point.*

Some n-gram data, a couple simple utility functions, and an example script for generating English-like words. Built to answer ["How can I generate a random logical word?"](http://stackoverflow.com/q/25966526/3942918) found at Stack Overflow.

## Requirements ##

PHP 5.6.3 or higher with the [GMP extension](http://php.net/gmp).

## Method & Data ##

What can make a word look somewhat logical is if it's composed of characters in an order you're used to seeing them. One way to do this is with a weighted list of [trigrams](http://en.wikipedia.org/wiki/Trigram) - sequences of 3 characters.

Basically you take any two letters, like "so", and add another that commonly comes after it, like "l". Then take the last two letters, "ol", and find what comes after that. Rinse/repeat until you've got a word of whatever length you'd like - *"solverom"*.

Sourcing from [Peter Norvig's n-gram data](http://norvig.com/mayzner.html) (which itself was compiled from [Google books ngrams](http://storage.googleapis.com/books/ngrams/books/datasetsv2.html)), I've put together a [few handy json files](https://github.com/pcrov/Wordle/tree/master/data).

The data can actually be compiled from any dictionary or other hulking word list, and is structured like so...

---

### [distinct_word_lengths.json](https://github.com/pcrov/Wordle/blob/master/data/distinct_word_lengths.json) ###

```json
    [0,26,622,4615,6977,10541,13341,...
```

This one is a (0-indexed) distribution of lengths of distinct words. Each index is the word length and each value how many words of that length were found. So, for example, there were 4615 distinct words that were 3 characters long.

We'll use this to decide how long our new word should be. Basically we add up all the values, pick a random number between 1 and the total, then find where in the set it lays. The key for that element is how long the word will be.

---

### [word_start_bigrams.json](https://github.com/pcrov/Wordle/blob/master/data/word_start_bigrams.json) ###

```json
    {
        "TH": "82191954206",
        "HE": "9112438473",
        "IN": "27799770674",
        "ER": "324230831",
        ...
```

This one couples bigrams, two-character combinations, with how often they're found at the beginning of words. Yes, everything is in capital letters.

We'll use this to decide what to start our word with.

---

### [trigrams.json](https://github.com/pcrov/Wordle/blob/master/data/trigrams.json) ###

```json
    {
        "TH": {
            "E": "69221160871",
            "A": "9447439870",
            "I": "6357454845",
            "O": "3369505315",
            "R": "1673179164",
            ...
        },
        "AN": {
            "D": "26468697834",
            "T": "3755591976",
            "C": "3061152975",
            ...
```

This one is a little more interesting. Each key in this data set is a bigram with an array of characters and how often that character appears after it.

"D" shows up after "AN" a lot.

This is what we'll use to build up the rest of the word.

---

## Functions ##

First we need a couple utility functions. Found in `src/functions.php`.

### array_weighted_rand() ###

```php
    function array_weighted_rand($list) { ... }
```

This is much like the built-in [`array_rand()`](http://php.net/array_rand) in that you pass it an array and it'll return a random key. Only this one factors in the weight when picking it.

So if you pass in an array that looks like:

```php
    [
      'foo' => 2,
      'bar' => 4,
      'baz' => 12
    ]
```

It'll return `bar` about twice as often as it'll return `foo`, and `baz` about three times as often as `bar`.

---

### fill_word() ###

```php
    function fill_word($word, $length, $trigrams) { ... }
```

This takes a string `$word` and fills it to `$length` from the set of given `$trigrams`. Each iteration it picks from the data set based on the last two characters in the string.

---

## Usage ##

### `example.php` ###

```php
    require_once __DIR__ . '/src/functions.php';

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

```

What we're doing is getting a random length, and random bigram to begin the word with, then filling it up. The [`preg_match()`](http://php.net/preg_match) is just to validate that the word contains a vowel, which isn't otherwise guaranteed. If it doesn't, try again.

You can replace this with any sort of validation you might want to do, such as making sure it doesn't match a real word in your database or whatever.

Yeah, you might generate a real word. Just pronounce it different if you want to say you made it up.

---

### Output ###

Running a handful of times landed me with these:

    ancover             ingennized          plesuri             asymbablew
    orkno               oftedi              nestrat             arlysect
    welvency            thembe              therespaid          frokedgerition
    judeth              ist                 rectede             privede
    aprommautu          offeleal            townerislo          callynerly
    thentsi             perma               themenum            agesputherflone
    pecticangenti       whoult              ifileyea            onster
    flatco              powne               prative             betion
    inegansith          meraddin            theste              mysistai
    skerest             uppre               ongdonc             hadmints

All of which my spell-checker hates.

---

Enjoy.
