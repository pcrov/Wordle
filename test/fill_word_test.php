<?php

namespace Wordle\Test;

use function \Wordle\fill_word;

class fill_word_test extends \PHPUnit_Framework_TestCase
{
    protected $trigrams = [
        ""   => ["F" => 1],
        "F"  => ["O" => 1],
        "FO" => ["S" => 1],
        "OS" => ["H" => 1],
        "SH" => ["I" => 1],
        "HI" => ["Z" => 1],
        "IZ" => ["Z" => 1],
        "ZZ" => ["L" => 1],
        "ZL" => ["E" => 1],
    ];

    public function testShortLength()
    {
        $this->assertEquals("FO", fill_word("FO", 1, $this->trigrams, 'key'));
    }

    public function testNoApplicableTrigram()
    {
        $this->assertEquals("FOSHIZZLE", fill_word("FO", 42, $this->trigrams, 'key'));
    }

    public function testWordFill()
    {
        $this->assertEquals("FOSHIZZLE", fill_word("FO", 9, $this->trigrams, 'key'));
    }

    public function testStartWordShort()
    {
        $this->assertEquals("FOSHIZZLE", fill_word("", 9, $this->trigrams, 'key'));
    }

    public function testStartWordLong()
    {
        $this->assertEquals("FOSHIZZLE", fill_word("FOSHIZ", 9, $this->trigrams, 'key'));
    }
}
