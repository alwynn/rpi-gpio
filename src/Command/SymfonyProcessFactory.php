<?php declare(strict_types=1);
/**
 * Raspberry PI GPIO library
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn <alwynn.github@gmail.com>
 */
namespace Rpi\Gpio\Command;

use Symfony\Component\Process\Process;
use Rpi\Gpio\CommandFactoryInterface;
use Rpi\Gpio\CommandInterface;

/**
 * Symfony based command factory
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn <alwynn.github@gmail.com>
 */
class SymfonyProcessFactory implements CommandFactoryInterface
{
    /** @inheritdoc */
    public function create(array $command): CommandInterface
    {
        return new SymfonyProcess(
            new Process($command)
        );
    }
}
