<?php declare(strict_types=1);
/**
 * Raspberry PI GPIO library
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn <alwynn.github@gmail.com>
 */
namespace Rpi\Gpio;

/**
 * WiringPI PHP interface
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn <alwynn.github@gmail.com>
 * @link    http://wiringpi.com/the-gpio-utility/
 */
interface WiringInterface
{
    /**
     * Operating mode that sets gpio command to interpret
     * pin numbers as BCM_GPIO numbers.
     * @var integer
     */
    const MODE_BCM = 0;

    /**
     * Operating mode that sets gpio command to interpret
     * pin numbers as physical numbers.
     * @var integer
     */
    const MODE_BOARD = 1;

    /**
     * Operating mode that sets gpio command to use
     * it's internal pin numbering.
     * @var integer
     */
    const MODE_WIRINGPI = 2;

    /**
     * Pin "in" mode
     * @var string
     */
    const PIN_MODE_IN = 'in';

    /**
     * Pin "out" mode
     * @var string
     */
    const PIN_MODE_OUT = 'out';

    /**
     * Pin "pwm" mode
     * @var string
     */
    const PIN_MODE_PWM = 'pwm';

    /**
     * Pin "clock" mode
     * @var string
     */
    const PIN_MODE_CLOCK = 'clock';

    /**
     * Pin "up" mode
     * @var string
     */
    const PIN_MODE_UP = 'up';

    /**
     * Pin "down" mode
     * @var string
     */
    const PIN_MODE_DOWN = 'down';

    /**
     * Pin "tri" mode
     * @var string
     */
    const PIN_MODE_TRI = 'tri';

    /**
     * Pin "rising" edge
     * @var string
     */
    const EDGE_RISING = 'rising';

    /**
     * Pin "falling" edge
     * @var string
     */
    const EDGE_FALLING = 'falling';

    /**
     * Pin "both" edge
     * @var string
     */
    const EDGE_BOTH = 'both';

    /**
     * Disabled pin edge
     * @var string
     */
    const EDGE_NONE = 'none';

    /**
     * Get wiring operating mode.
     * @return int Operating mode
     */
    public function getMode(): int;

    /**
     * Exports a pin as input or output
     *
     * @param  int             $pin  Pin number
     * @param  string          $mode Pin mode (in/out)
     * @return WiringInterface
     *
     * @link   http://wiringpi.com/the-gpio-utility/ Ref.: the gpio export command
     */
    public function export(int $pin, string $mode): WiringInterface;

    /**
     * Unexports the pin
     *
     * @param  int             $pin Pin number to unexport
     * @return WiringInterface
     *
     * @link   http://wiringpi.com/the-gpio-utility/ Ref.: the gpio unexport command
     */
    public function unexport(int $pin): WiringInterface;

    /**
     * Unexports all previously exported pins
     *
     * @return WiringInterface
     *
     * @link   http://wiringpi.com/the-gpio-utility/ Ref.: the gpio unexportall command
     */
    public function unexportall(): WiringInterface;

    /**
     * Sets the mode of the pin
     *
     * @param  int             $pin  Pin to set the mode of
     * @param  string          $mode Mode of the pin
     * @return WiringInterface
     *
     * @link   http://wiringpi.com/the-gpio-utility/ Ref.: the gpio mode command
     */
    public function mode(int $pin, string $mode): WiringInterface;

    /**
     * Read a value from the pin
     *
     * @param  int $pin Pin to read the value from
     * @return int      Logic value of the pin (0 - low, 1 - high)
     *
     * @link   http://wiringpi.com/the-gpio-utility/ Ref.: the gpio read command
     */
    public function read(int $pin): int;

    /**
     * Write a value to the pin
     *
     * @param  int             $pin   Pin to set the value of
     * @param  int             $value Value to write (0 - low, 1 - high)
     * @return WiringInterface
     *
     * @link   http://wiringpi.com/the-gpio-utility/ Ref.: the gpio write command
     */
    public function write(int $pin, int $value): WiringInterface;

    /**
     * Enables the pin for edge interrupt triggering
     *
     * @param  int             $pin  Pin
     * @param  string          $edge Triggering edge, "none" to disable
     * @return WiringInterface
     *
     * @link   http://wiringpi.com/the-gpio-utility/ Ref.: the gpio edge command
     */
    public function edge(int $pin, string $edge): WiringInterface;
}
