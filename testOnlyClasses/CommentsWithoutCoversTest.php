<?php
class CommentsWithoutCoversTest extends \PHPUnit\Framework\TestCase
{
    /**
     * This has a comment, but no @covers...
     */
    function testMinimalFail()
    {
        //Should fail.
    }
}