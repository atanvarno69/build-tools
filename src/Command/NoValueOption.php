<?php

/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Command;

class NoValueOption implements Option
{
    use OptionTrait;

    public function getSuffix(): string
    {
        return '';
    }

    public function getValue(): ?string
    {
        return null;
    }

    public function setValue(string $value): void
    {
    }
}
