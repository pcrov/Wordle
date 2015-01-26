<?php

namespace Wordle\Test;

use function \Wordle\array_weighted_rand;

class array_weighted_rand_test extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage 0 => -1
     */
    public function testExceptionIsRaisedForNegativeWeight()
    {
        array_weighted_rand([-1]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Total weight must exceed zero.
     */
    public function testExceptionIsRaisedForZeroTotalWeight()
    {
        array_weighted_rand([0]);
    }

    public function testWeightTotalOneReturnsProperKey()
    {
        $this->assertEquals(1, array_weighted_rand([0, 1, 0]));
    }

    public function testReturnsAnyValidKey()
    {
        $key = array_weighted_rand([0, 0, 42, gmp_init(PHP_INT_MAX)*2, 42, 0]);
        $this->assertGreaterThan(1, $key);
        $this->assertLessThan(5, $key);
    }
}
