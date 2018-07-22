<?php declare(strict_types=1);
/**
 * Raspberry PI GPIO library
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn <alwynn.github@gmail.com>
 */
namespace Rpi\Gpio;

/**
 * Command execution interface
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn <alwynn.github@gmail.com>
 */
interface CommandInterface
{
    /**
     * Executes a command and retrieves its output.
     *
     * @return string                           Output of a command
     * @throws Exception\CommandFailedException On invalid command or on output other than 0
     */
    public function execute(): string;
}
