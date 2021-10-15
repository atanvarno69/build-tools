<?php

/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Test;

/**
 * Immutable data object for command line output.
 */
class CommandLineOutput
{
    /**
     * Create `CommandLineOutput` instance.
     *
     * @param int    $code   Output status code. Required.
     * @param string $stdout STDOUT output. Optional, defaults to empty string.
     * @param string $stderr STDERR output. Optional, defaults to empty string.
     */
    public function __construct(
        protected int $code,
        protected string $stdout = '',
        protected string $stderr = '',
    ) {}

    /**
     * STDOUT output.
     */
    public function stdout(): string
    {
        return $this->stdout;
    }

    /**
     * STDERR output.
     */
    public function stderr(): string
    {
        return $this->stderr;
    }

    /**
     * Status code output.
     */
    public function code(): int
    {
        return $this->code;
    }
}
