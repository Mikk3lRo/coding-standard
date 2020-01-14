<?php declare(strict_types = 1);

namespace Mikk3lRo\Tests;

use PHPUnit\Framework\TestCase;

final class NoCoversFirstTest extends TestCase
{
    public function testNoCoversOnThisMethod()
    {
        //This one triggers an error
    }


    /**
     * @covers Something
     */
    public function testHasCovers()
    {
        //This one does not trigger an error
    }
}