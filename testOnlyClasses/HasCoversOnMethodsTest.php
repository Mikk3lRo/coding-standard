<?php declare(strict_types = 1);

namespace Mikk3lRo\atomix\Tests;

use PHPUnit\Framework\TestCase;

final class HasCoversOnMethodsTest extends TestCase
{
    /**
     * @covers Something
     */
    public function testHasCovers1()
    {
        //This one does not trigger an error
    }


    /**
     * @covers Something
     */
    public function testHasCovers2()
    {
        //This one does not trigger an error
    }
}