<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testAddition()
    {
        $this->assertEquals(4, 2 + 2);

        // MAKE SURE TO USE MAGIC CONSTANTS FOR TEST & DEBUG
        // __DIR__ is the directory of the current file
        // __FILE__ is the full path and filename of the file
        // __LINE__ is the current line number of the file
        // __CLASS__ is the class name
        // __METHOD__ is the method name
        // __FUNCTION__ is the function name
        // __NAMESPACE__ is the name of the current namespace
        // __TRAIT__ is the trait name 
    }
}

?>