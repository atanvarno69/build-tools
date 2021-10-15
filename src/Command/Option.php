<?php

/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Command;

interface Option
{
    public function getDescription(): string;

    public function getId(): string;

    public function getLong(): ?string;

    public function getShort(): ?string;

    public function getSuffix(): string;

    public function getValue(): ?string;

    public function matches(string $value): bool;

    public function isSet(): bool;

    public function setValue(?string $value = null): void;
}
