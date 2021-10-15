<?php

/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Command\Option;

trait ValueOptionTrait
{
    use OptionTrait;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value = null): void
    {
        $this->set = true;
        $this->value = $value;
    }
}
