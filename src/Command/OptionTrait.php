<?php

/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Command;

trait OptionTrait
{
    protected ?string $value = null;

    public function __construct(
        protected string $long = '',
        protected string $short = '',
        protected string $description = '',
    ) {
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLong(): ?string
    {
        return $this->long;
    }

    public function getShort(): ?string
    {
        return $this->short;
    }

    abstract public function getSuffix(): string;

    abstract public function getValue(): ?string;

    abstract public function setValue(string $value): void;
}
