<?php declare(strict_types=1);
/**
 * Raspberry PI GPIO library
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn <alwynn.github@gmail.com>
 */
namespace Rpi\Gpio;

use Rpi\Gpio\Exception;

/**
 * WiringPI PHP interface
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn <alwynn.github@gmail.com>
 * @link    http://wiringpi.com/the-gpio-utility/
 */
final class Wiring implements WiringInterface
{
    /**
     * Base gpio command path
     * @var array
     */
    protected $command;

    /**
     * WiringPi operating mode. Can be one of:
     * - Wiring::MODE_BCM
     * - Wiring::MODE_BOARD
     * - Wiring:;MODE_WIRINGPI
     *
     * Wiring::MODE_BCM will add "-g" parameter to gpio command,
     * making it interpret pins' numbers as BCM_GPIO numbers.
     * Wiring::MODE_BOARD will add "-1" parameter to gpio command,
     * making it interpret pins' numbers as physical numbers.
     *
     * @var int
     */
    protected $mode;

    /**
     * Command factory
     * @var CommandFactoryInterface
     */
    protected $factory;

    /**
     * Available class's operating modes
     * @var array
     */
    protected static $operatingModes = [
        self::MODE_BCM,
        self::MODE_BOARD,
        self::MODE_WIRINGPI
    ];

    /**
     * Available pins` modes
     * @var array
     */
    protected static $pinModes = [
        self::PIN_MODE_IN,
        self::PIN_MODE_OUT,
        self::PIN_MODE_PWM,
        self::PIN_MODE_CLOCK,
        self::PIN_MODE_DOWN,
        self::PIN_MODE_UP,
        self::PIN_MODE_TRI,
    ];

    /**
     * Available pins' edges
     * @var array
     */
    protected static $pinEdges = [
        self::EDGE_RISING,
        self::EDGE_FALLING,
        self::EDGE_BOTH,
        self::EDGE_NONE
    ];

    /**
     * @param int                     $mode    Operating mode
     * @param CommandFactoryInterface $factory Command factory
     * @param string                  $command Wiringpi GPIO command. Defaults to /usr/bin/gpio
     */
    public function __construct(
        int $mode,
        CommandFactoryInterface $factory,
        string $command = '/usr/bin/gpio'
    ) {
        if (! in_array($mode, static::$operatingModes)) {
            throw new Exception\InvalidOperatingMode(sprintf(
                "Invalid operating mode \"{$mode}\". Available modes are ".
                'Wiring::MODE_BCM, Wiring::MODE_BOARD and Wiring::MODE_WIRINGPI'
            ));
        }

        $this->command = [$command];

        if ($mode == static::MODE_BCM) {
            $this->command[] = '-g';
        } elseif ($mode == static::MODE_BOARD) {
            $this->command[] = '-1';
        }

        $this->mode    = $mode;
        $this->factory = $factory;
    }

    /** @inheritdoc */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     * Prepares a command array to execution
     * @param  array $command An array with command components
     * @return array          An array with prepared command
     */
    protected function prepare(array $command): array
    {
        return array_merge($this->command, $command);
    }

    /**
     * Executes a command and returns it's output
     *
     * @param  array  $command An array with prepared command
     * @return string          Output from a command
     */
    protected function execute(array $command): string
    {
        $command = $this->prepare($command);
        $process = $this->factory->create($command);

        return $process->execute();
    }

    /** @inheritdoc */
    public function export(int $pin, string $mode): WiringInterface
    {
        if ($mode != static::PIN_MODE_IN and $mode != static::PIN_MODE_OUT) {
            throw new Exception\InvalidPinMode(
                "Invalid pin mode \"{$mode}\". Available modes are: " .
                'Wiring::PIN_MODE_IN and Wiring::PIN_MODE_OUT'
            );
        }

        $this->execute(['export', $pin, $mode]);

        return $this;
    }

    /** @inheritdoc */
    public function unexport(int $pin): WiringInterface
    {
        $this->execute(['unexport', $pin]);

        return $this;
    }

    /** @inheritdoc */
    public function unexportall(): WiringInterface
    {
        $this->execute(['unexportall']);

        return $this;
    }

    /** @inheritdoc */
    public function mode(int $pin, string $mode): WiringInterface
    {
        if (! in_array($mode, static::$pinModes)) {
            throw new Exception\InvalidPinMode(
                "Invalid pin mode \"{$mode}\". Available modes are: Wiring::PIN_MODE_IN, ".
                'Wiring::PIN_MODE_OUT, Wiring::PIN_MODE_PWM, Wiring::PIN_MODE_CLOCK ' .
                'Wiring::PIN_MODE_DOWN, Wiring::PIN_MODE_UP and Wiring::PIN_MODE_TRI.'
            );
        }

        $this->execute(['mode', $pin, $mode]);

        return $this;
    }

    /** @inheritdoc */
    public function read(int $pin): int
    {
        $result = $this->execute(['read', $pin]);

        return intval($result);
    }

    /** @inheritdoc */
    public function write(int $pin, int $value): WiringInterface
    {
        if ($value != 0 and $value != 1) {
            throw new Exception\InvalidPinValue(
                "Invalid pin value \"{$value}\". Available values are \"0\" and \"1\"."
            );
        }

        $this->execute(['write', $pin, $value]);

        return $this;
    }

    /** @inheritdoc */
    public function edge(int $pin, string $edge): WiringInterface
    {
        if (! in_array($edge, static::$pinEdges)) {
            throw new Exception\InvalidPinEdge(
                "Invalid pin edge \"{$edge}\". Available values are: Wiring::EDGE_RISING, " .
                'Wiring::EDGE_FALLING, Wiring::EDGE_BOTH and Wiring::EDGE_NONE.'
            );
        }

        $this->execute(['edge', $pin, $edge]);

        return $this;
    }

    /**
     * Returns available wiring operating modes
     * @return array Operating modes
     */
    public static function getOperatingModes(): array
    {
        return static::$operatingModes;
    }

    /**
     * Returns available pin modes
     * @return array Available pin modes
     */
    public static function getPinModes(): array
    {
        return static::$pinModes;
    }

    /**
     * Returns available pin edges
     * @return array Available pin edges
     */
    public static function getPinEdges(): array
    {
        return static::$pinEdges;
    }
}
