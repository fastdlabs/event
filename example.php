<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

include __DIR__ . '/vendor/autoload.php';

$provider = new \FastD\Event\Provider();

$dispatcher = new \FastD\Event\Dispatcher($provider);

$provider->attach(function () {
    echo 'hello'.PHP_EOL;
});

print_r($dispatcher->dispatch());
