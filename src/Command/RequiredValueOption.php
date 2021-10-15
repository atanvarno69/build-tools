<?php

/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Command;

class RequiredValueOption
{
    use OptionTrait;

    public function getSuffix(): string
    {
        return '';
    }

    public function getValue(): ?string
    {
        // TODO: Implement getValue() method.
    }

    public function setValue(string $value): void
    {
        // TODO: Implement setValue() method.
    }
}
