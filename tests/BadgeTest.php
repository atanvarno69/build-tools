<?php

/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Test;

use PHPUnit\Framework\TestCase;

class BadgeTest extends TestCase
{
    protected string $color;
    protected string $percent;
    protected string $xml;

    public function setUp(): void
    {
        $this->color = dirname(__FILE__, 2) . '/bin/badge-color';
        $this->percent = dirname(__FILE__, 2) . '/bin/badge-percent';
        $this->xml = __DIR__ . '/resources/clover-100.xml';
    }

    public function testColorScript(): void
    {
        $actual = CommandLineResult::fromCommand($this->color, $this->xml);
        $this->assertEquals(0, $actual->code());
        $this->assertEquals('COLOR=success', $actual->stdout());
    }

    public function testPercentScript(): void
    {
        $actual = CommandLineResult::fromCommand($this->percent, $this->xml);
        $this->assertEquals(0, $actual->code());
        $this->assertEquals('PERCENT=100%', $actual->stdout());
    }
}
