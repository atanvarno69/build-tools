<?php

/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Command\Option;

use Exception;

trait OptionTrait
{
    protected bool $set = false;

    protected ?string $value = null;

    /**
     * @throws Exception Both long and short are not given.
     */
    public function __construct(
        protected ?string $long = null,
        protected ?string $short = null,
        protected string $description = '',
    ) {
        if ($long === null && $short === null) {
            throw new Exception('Either long or short must be given, or both');
        }
    }

    public function matches(string $value): bool
    {
        return $value === $this->long || $value === $this->short;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getId(): string
    {
        return $this->long ?? $this->short ?? '';
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

    public function isSet(): bool
    {
        return $this->set;
    }

    abstract public function setValue(?string $value = null): void;
}
