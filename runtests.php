<?php
chdir(__DIR__);
succeedOrDie('chmod +x vendor/bin/phpunit vendor/phpunit/phpunit/phpunit');
succeedOrDie('chmod +x vendor/bin/phpcs vendor/squizlabs/php_codesniffer/bin/phpcs vendor/bin/phpcbf vendor/squizlabs/php_codesniffer/bin/phpcbf');

//Fix reference to vendor folder for coding_standards package
if (strpos(file_get_contents('composer.json'), '"name": "mikk3lro/coding-standards"') !== false) {
    file_put_contents('phpcsTests.xml', str_replace('vendor/mikk3lro/coding-standards/', '', file_get_contents('phpcsTests.xml')));
}

if (isset($argv[1]) && $argv[1] === 'coverage') {
    passthru('vendor/bin/phpunit --coverage-html=/var/www/html/ --whitelist src');
    exit();
}

if (isset($argv[1]) && $argv[1] === 'autofix') {
    passthru('vendor/bin/phpcbf -s --standard=phpcsTests.xml');
    exit();
}

echo "******** UNIT TESTS ********\n";
if (isset($argv[1])) {
    if (file_exists('tests/' . $argv[1] . 'Test.php')) {
        succeedOrDie('vendor/bin/phpunit tests/' . $argv[1] . 'Test.php');
    }
} else {
    succeedOrDie('vendor/bin/phpunit');
}

echo "******** CODE SNIFF (src) ********\n";
succeedOrDie('vendor/bin/phpcs -s');

echo "******** CODE SNIFF (tests) ********\n";
succeedOrDie('vendor/bin/phpcs -s --standard=phpcsTests.xml');

echo "******** ALL TESTS SUCCEEDED ********\n";
exit(0);

function succeedOrDie($cmd) {
    passthru($cmd, $retval);
    if ($retval !== 0) {
        echo "Command failed:\n    $cmd\n";
        exit(1);
    }
}