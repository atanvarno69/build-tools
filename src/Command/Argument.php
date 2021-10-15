<?php
/**
 * @package   Atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Command;

class Argument
{
    protected ?string $value = null;

    public function __construct(
        protected ?string $name = null,
        protected ?string $description = null,
    ) {}

    public function getName(): string
    {
        return $this->name ?? '';
    }

    public function getDescription(): string
    {
        return $this->description ?? '';
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
