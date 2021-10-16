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
 * Immutable data object for command line results.
 */
class CommandLineResult
{
    /**
     * Run a command and capture its outputs.
     */
    public static function fromCommand(string $command, ?string $arguments = null): self
    {
        if ($arguments !== null && trim($arguments) !== '') {
            $command = implode(' ', [trim($command), trim($arguments)]);
        }
        $spec = [
            1 => ['pipe', 'w'], // STDOUT
            2 => ['pipe', 'w'], // STDERR
        ];
        $process = proc_open($command, $spec, $pipes);
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $code = proc_close($process);
        return new self($code, $stdout, $stderr);
    }

    /**
     * Set a command output values.
     */
    public static function fromValues(int $code, string $stdout = '', string $stderr = ''): self
    {
        return new self($code, $stdout, $stderr);
    }

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

    /**
     * Create `CommandLineOutput` instance.
     *
     * Use `fromCommand()` or `fromValue()` instead.
     */
    protected function __construct(
        protected int $code,
        protected string $stdout,
        protected string $stderr,
    ) {
    }
}
