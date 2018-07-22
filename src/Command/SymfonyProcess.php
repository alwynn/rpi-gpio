<?php declare(strict_types=1);
/**
 * Raspberry PI GPIO library
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn <alwynn.github@gmail.com>
 */
namespace Rpi\Gpio\Command;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Rpi\Gpio\CommandInterface;
use Rpi\Gpio\Exception\CommandFailedException;

/**
 * Symfony based command implementation
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn <alwynn.github@gmail.com>
 */
class SymfonyProcess implements CommandInterface
{
    /**
     * Process object
     *
     * @var Process
     */
    protected $process;

    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /** @inheritdoc */
    public function execute(): string
    {
        try {
            $this->process->mustRun();

            if (! $this->process->isSuccessful()) {
                throw new CommandFailedException(sprintf(
                    'Command "%s" failed with status %d',
                    $this->process->getCommandLine(),
                    $this->process->getExitCode()
                ));
            }

            return trim($this->process->getOutput());
        } catch (ProcessFailedException $exception) {
            throw new CommandFailedException(sprintf(
                'Command "%s" failed with status %d',
                $this->process->getCommandLine(),
                $this->process->getExitCode()
            ), 0, $exception);
        }
    }
}
