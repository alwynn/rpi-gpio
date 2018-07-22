<?php declare(strict_types=1);
/**
 * Raspberry PI GPIO library
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn <alwynn.github@gmail.com>
 */
namespace Rpi\Gpio;

/**
 * A factory for creating a command object
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn <alwynn.github@gmail.com>
 */
interface CommandFactoryInterface
{
    /**
     * Creates a process object for given command
     *
     * @param  array            $command Command description
     * @return CommandInterface          Command object
     */
    public function create(array $command): CommandInterface;
}
