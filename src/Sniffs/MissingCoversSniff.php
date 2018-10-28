<?php declare(strict_types = 1);

namespace Mikk3lRo\coding_standards\Sniffs;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

class MissingCoversSniff implements Sniff
{
    /**
     * @return array
     */
    public function register() : array
    {
        return array(
            T_CLASS
        );
    }


    /**
     * @param File  $file    File.
     * @param mixed $pointer Position in stack.
     *
     * @return void
     */
    public function process(File $file, $pointer) : void
    {
        $className = $file->getDeclarationName($pointer);

        if (substr($className, -4) !== 'Test') {
            //Not a test class
            return;
        }

        if ($this->hasCoversTag($file, $pointer)) {
            //Class has @covers
            return;
        }

        //No @covers on class - check all methods individually
        $tokens = $file->getTokens();
        $classCloser = $tokens[$pointer]['scope_closer'];

        while (true) {
            $pointer = $file->findNext(
                array(T_FUNCTION),
                $pointer + 1,
                $classCloser
            );

            if (!$pointer) {
                // Finished class
                break;
            }

            $methodName = $file->getDeclarationName($pointer);

            if (substr($methodName, 0, 4) !== 'test') {
                // Not a test method
                continue;
            }

            $hasCovers = $this->hasCoversTag($file, $pointer);
            if (!$hasCovers) {
                $file->addError(
                    "$methodName has no @covers tag, add a @covers tag to the $className class or all its test methods!",
                    $pointer,
                    'MissingCovers'
                );
            }
        }
    }


    /**
     * Checks if the statement has a @covers tag.
     *
     * @param File  $file    File.
     * @param mixed $pointer Position in stack.
     *
     * @return boolean
     */
    protected function hasCoversTag(File $file, $pointer)
    {
        $notIn = Tokens::$methodPrefixes;
        $notIn[] = T_WHITESPACE;

        $closer = $file->findPrevious($notIn, $pointer - 1, 0, true);

        $tokens = $file->getTokens();
        $token = $tokens[$closer];

        if ($token['code'] !== T_DOC_COMMENT_CLOSE_TAG) {
            // No doc comment
            return false;
        }

        $opener = $tokens[$closer]['comment_opener'];
        $tags = $tokens[$opener]['comment_tags'];

        foreach ($tags as $tag) {
            $name = $tokens[$tag]['content'];
            if ($name === '@covers' || $name === '@coversNothing') {
                return true;
            }
        }
        return false;
    }
}
