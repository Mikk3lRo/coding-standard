<?php declare(strict_types = 1);

namespace Mikk3lRo\Tests;

use PHPUnit\Framework\TestCase;

final class NoCoversLastTest extends TestCase
{
    /**
     * @covers Something
     */
    public function testHasCovers()
    {
        //This one does not trigger an error
    }

    
    public function notATestFunction()
    {
        //This one does not trigger an error
    }


    public function testNoCoversOnThisMethod()
    {
        //This one triggers an error
    }
}