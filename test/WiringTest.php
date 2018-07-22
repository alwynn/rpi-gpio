<?php declare(strict_types=1);
/**
 * Raspberry PI GPIO library
 *
 * @package alwynn/rpi-gpio
 * @author  Alwynn
 */
namespace Rpi\Gpio;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;
use Rpi\Gpio\Exception\InvalidOperatingMode;
use Rpi\Gpio\Exception\InvalidPinMode;
use Rpi\Gpio\Exception\InvalidPinValue;
use Rpi\Gpio\Exception\InvalidPinEdge;

function array_carthesian_product($array1, $array2)
{
    $result = [];
    foreach ($array1 as $row1) {
        foreach ($array2 as $row2) {
            $result[] = [$row1, $row2];
        }
    }

    return $result;
}

class WiringTest extends TestCase
{
    public function testgettingAvailableOperatingModes()
    {
        $this->assertInternalType('array', Wiring::getOperatingModes());
    }

    public function testGettingAvailabePinModes()
    {
        $this->assertInternalType('array', Wiring::getPinModes());
    }

    public function testGettingAvailablePinEdges()
    {
        $this->assertInternalType('array', Wiring::getPinEdges());
    }

    public function testInstanceOnInvalidOperatingMode()
    {
        $this->expectException(InvalidOperatingMode::class);

        $mode = Wiring::getOperatingModes();
        $mode = max($mode);
        $mode ++;

        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);

        $wiring = new Wiring($mode, $factory);
    }

    /** @dataProvider provideOperatingModes */
    public function testInstance($mode)
    {
        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);
        $wiring  = new Wiring($mode, $factory);

        $this->assertInstanceOf(WiringInterface::class, $wiring);
    }

    /** @dataProvider provideOperatingModes */
    public function testGettingOperatingMode($mode)
    {
        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);
        $wiring  = new Wiring($mode, $factory);

        $this->assertEquals($mode, $wiring->getMode());
    }

    /** @dataProvider provideOperatingModesAndInvalidPinExportModes */
    public function testExportCommandOnInvalidPinMode($boardMode, $pinMode)
    {
        $this->expectException(InvalidPinMode::class);

        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);
        $wiring  = new Wiring($boardMode, $factory);

        $wiring->export(1, $pinMode);
    }

    /** @dataProvider provideBoardModesAndPinExportModes */
    public function testExportCommand($boardMode, $pinMode)
    {
        $command = $this->getMockForAbstractClass(CommandInterface::class);
        $command->expects($this->once())->method('execute');

        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);
        $factory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($command));

        $wiring  = new Wiring($boardMode, $factory);

        $this->assertSame($wiring, $wiring->export(1, $pinMode));
    }

    /** @dataProvider provideOperatingModes */
    public function testUnexportCommand($mode)
    {
        $command = $this->getMockForAbstractClass(CommandInterface::class);
        $command->expects($this->once())->method('execute');

        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);
        $factory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($command));

        $wiring  = new Wiring($mode, $factory);

        $this->assertSame($wiring, $wiring->unexport(1));
    }

    /** @dataProvider provideOperatingModes */
    public function testUnexportallCommand($mode)
    {
        $command = $this->getMockForAbstractClass(CommandInterface::class);
        $command->expects($this->once())->method('execute');

        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);
        $factory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($command));

        $wiring  = new Wiring($mode, $factory);

        $this->assertSame($wiring, $wiring->unexportall());
    }

    /** @dataProvider provideOperatingModesAndInvalidModePinModes */
    public function testModeCommandOnInvalidPinMode($boardMode, $pinMode)
    {
        $this->expectException(InvalidPinMode::class);

        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);
        $wiring  = new Wiring($boardMode, $factory);
        $wiring->mode(1, $pinMode);
    }

    /** @dataProvider provideOperatinModesWithPinModes */
    public function testModeCommand($mode, $pinMode)
    {
        $command = $this->getMockForAbstractClass(CommandInterface::class);
        $command->expects($this->once())->method('execute');

        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);
        $factory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($command));

        $pin = 1;
        $wiring = new Wiring($mode, $factory);

        $this->assertSame($wiring, $wiring->mode($pin, $pinMode));
    }

    /** @dataProvider provideOperatingModesAndWriteValues */
    public function testReadCommand($mode, $value)
    {
        $command = $this->getMockForAbstractClass(CommandInterface::class);
        $command->expects($this->once())
            ->method('execute')
            ->willReturn($value);

        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);
        $factory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($command));

        $pin = 1;
        $wiring = new Wiring($mode, $factory);

        $this->assertEquals($value, $wiring->read($pin));
    }

    /** @dataProvider provideOperatingModesAndWriteValues */
    public function testWriteCommand($mode, $value)
    {
        $command = $this->getMockForAbstractClass(CommandInterface::class);
        $command->expects($this->once())->method('execute');

        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);
        $factory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($command));

        $pin = 1;
        $wiring = new Wiring($mode, $factory);

        $this->assertSame($wiring, $wiring->write($pin, $value));
    }

    /** @dataProvider provideOperatingModesAndInvalidPinValues */
    public function testWriteCommandOnInvalidValues($mode, $value)
    {
        $this->expectException(InvalidPinValue::class);

        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);
        $wiring  = new Wiring($mode, $factory);

        $wiring->write(1, $value);
    }

    /** @dataProvider provideOperatingModesAndInvalidPinEdges */
    public function testEdgeCommandOnInvalidEdgeValue($mode, $edge)
    {
        $this->expectException(InvalidPinEdge::class);

        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);
        $wiring  = new Wiring($mode, $factory);

        $wiring->edge(1, $edge);
    }

    /** @dataProvider provideOperatingModesAndPinEdges */
    public function testEdgeCommand($mode, $edge)
    {
        $command = $this->getMockForAbstractClass(CommandInterface::class);
        $command->expects($this->once())->method('execute');

        $factory = $this->getMockForAbstractClass(CommandFactoryInterface::class);
        $factory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($command));

        $pin = 1;
        $wiring = new Wiring($mode, $factory);

        $this->assertSame($wiring, $wiring->edge($pin, $edge));
    }

    /*
        DATA PROVIDERS
     */

    public function provideOperatingModes()
    {
        return [
            [Wiring::MODE_BCM],
            [Wiring::MODE_BOARD],
            [Wiring::MODE_WIRINGPI]
        ];
    }

    public function provideBoardModesAndPinExportModes()
    {
        return array_carthesian_product(
            Wiring::getOperatingModes(),
            [Wiring::PIN_MODE_IN, Wiring::PIN_MODE_OUT]
        );
    }

    public function provideOperatinModesWithPinModes()
    {
        return array_carthesian_product(
            Wiring::getOperatingModes(),
            Wiring::getPinModes()
        );
    }

    public function provideOperatingModesAndWriteValues()
    {
        return array_carthesian_product(
            Wiring::getOperatingModes(),
            [0, 1]
        );
    }

    public function provideOperatingModesAndPinEdges()
    {
        return array_carthesian_product(
            Wiring::getOperatingModes(),
            Wiring::getPinEdges()
        );
    }

    public function provideOperatingModesAndInvalidPinExportModes()
    {
        return array_carthesian_product(
            Wiring::getOperatingModes(),
            [
                '',
                ' ',
                'some invalid mode',
                Wiring::PIN_MODE_UP,
                Wiring::PIN_MODE_PWM,
                Wiring::PIN_MODE_TRI,
                Wiring::PIN_MODE_DOWN,
                Wiring::PIN_MODE_CLOCK
            ]
        );
    }

    public function provideOperatingModesAndInvalidModePinModes()
    {
        return array_carthesian_product(
            Wiring::getOperatingModes(),
            ['', ' ', ' some mode']
        );
    }

    public function provideOperatingModesAndInvalidPinValues()
    {
        return array_carthesian_product(
            Wiring::getOperatingModes(),
            [-1, 2]
        );
    }

    public function provideOperatingModesAndInvalidPinEdges()
    {
        return array_carthesian_product(
            Wiring::getOperatingModes(),
            ['', ' ', 'invalid mode']
        );
    }
}
