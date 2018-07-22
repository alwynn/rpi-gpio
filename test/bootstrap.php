<?php declare(strict_types=1);
/**
 * Raspberry PI GPIO library
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn
 */

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('Rpi\\Gpio\\', __DIR__);

return $loader;
