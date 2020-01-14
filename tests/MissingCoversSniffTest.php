<?php declare(strict_types = 1);

namespace Mikk3lRo\Tests;

require_once(__DIR__ . '/../vendor/squizlabs/php_codesniffer/tests/bootstrap.php');

use PHPUnit\Framework\TestCase;
use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Config;

/**
 * @covers Mikk3lRo\coding_standards\Sniffs\MissingCoversSniff
 */
final class MissingCoversSniffTest extends TestCase
{
    public function runOnFile(string $fixtureFile) : array
    {
        $sniffFiles = [__DIR__ . '/../src/Sniffs/MissingCoversSniff.php'];
        $config = new Config();
        $ruleset = new Ruleset($config);
        $ruleset->registerSniffs($sniffFiles, [], []);
        $ruleset->populateTokenListeners();
        $phpcsFile = new LocalFile($fixtureFile, $ruleset, $config);
        $phpcsFile->process();
        $foundErrors = $phpcsFile->getErrors();
        return array_keys($foundErrors);
    }


    public function testHasCoversOnClassPasses()
    {
        $fixtureFile = __DIR__ . '/../testOnlyClasses/HasCoversOnClassTest.php';
        $this->assertEquals(array(), $this->runOnFile($fixtureFile));
    }


    public function testHasCoversOnMethodsPasses()
    {
        $fixtureFile = __DIR__ . '/../testOnlyClasses/HasCoversOnMethodsTest.php';
        $this->assertEquals(array(), $this->runOnFile($fixtureFile));
    }


    public function testNoCoversFirstFails()
    {
        $fixtureFile = __DIR__ . '/../testOnlyClasses/NoCoversFirstTest.php';
        $this->assertEquals(array(9), $this->runOnFile($fixtureFile));
    }


    public function testNoCoversLastFails()
    {
        $fixtureFile = __DIR__ . '/../testOnlyClasses/NoCoversLastTest.php';
        $this->assertEquals(array(24), $this->runOnFile($fixtureFile));
    }


    public function testMinimalFails()
    {
        $fixtureFile = __DIR__ . '/../testOnlyClasses/MinimalFailureTest.php';
        $this->assertEquals(array(4), $this->runOnFile($fixtureFile));
    }


    public function testCommentsWithoutCoversTestFails()
    {
        $fixtureFile = __DIR__ . '/../testOnlyClasses/CommentsWithoutCoversTest.php';
        $this->assertEquals(array(7), $this->runOnFile($fixtureFile));
    }


    public function testSkipsNotTestClass()
    {
        $fixtureFile = __DIR__ . '/../testOnlyClasses/NotTestClass.php';
        $this->assertEquals(array(), $this->runOnFile($fixtureFile));
    }


    public function testNoCommentsTest()
    {
        $fixtureFile = __DIR__ . '/../testOnlyClasses/NoCommentsTest.php';
        $this->assertEquals(array(9), $this->runOnFile($fixtureFile));
    }
}
