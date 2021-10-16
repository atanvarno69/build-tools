<?php

/**
 * @package   Atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Command;

use Atanvarno\BuildTools\Command\Option\NoValueOption;
use Throwable;

abstract class Command
{
    public const STATUS_ERROR = 1;
    public const STATUS_SUCCESS = 0;

    /** @var array<string, Option> $options */
    protected array $options = [];

    /**
     * @param array<array-key, Option>   $options
     * @param array<array-key, Argument> $arguments
     */
    public function __construct(
        protected string $name,
        array $options = [],
        protected array $arguments = [],
        protected ?string $description = null,
    ) {
        array_unshift($options, new NoValueOption(long: 'help', short: 'h', description: 'Print this help message.'));
        foreach ($options as $option) {
            $this->options[$option->getId()] = $option;
        }
    }

    /**
     * @param array<array-key, string> $input Use $argv.
     */
    public function execute(array $input): void
    {
        try {
            $options = getopt($this->getoptShort(), $this->getoptLong(), $index);
            foreach ($options as $key => $value) {
                foreach ($this->options as $option) {
                    if ($option->matches($key)) {
                        if (is_array($value)) {
                            $set = (string) count($value);
                        } elseif (is_string($value)) {
                            $set = $value;
                        } else {
                            $set = null;
                        }
                        $option->setValue($set);
                        break;
                    }
                }
            }
            $arguments = array_slice($input, $index);
            $i = 0;
            foreach ($arguments as $value) {
                if (!isset($this->arguments[$i])) {
                    break;
                }
                $this->arguments[$i]->setValue($value);
            }
            $result = $this->run();
            echo $result;
            exit(self::STATUS_SUCCESS);
        } catch (Throwable $caught) {
            fwrite(STDERR, sprintf('Error: %s%s', $caught->getMessage(), PHP_EOL));
            exit(self::STATUS_ERROR);
        }
    }

    protected function getHelpText(): string
    {
        $output = '';
        if ($this->description !== null) {
            $output .= sprintf('%s%s%s', $this->wrapText($this->description, 80, 0), PHP_EOL, PHP_EOL);
        }
        $argumentNames = [];
        foreach ($this->arguments as $argument) {
            $argumentNames[] = sprintf('<%s>', $argument->getName());
        }
        $argumentNames = implode(' ', $argumentNames);
        $output .= sprintf('Usage: %s [options] %s%s%s', $this->name, $argumentNames, PHP_EOL, PHP_EOL);
        $longest = 0;
        foreach ($this->options as $option) {
            $long = $option->getLong();
            if ($long !== null) {
                $length = strlen($long);
                $longest = $length > $longest ? $length : $longest;
            }
        }
        $indent = 10 + $longest;
        $lineLength = 80 - $indent;
        $optionLines = ['Options:'];
        foreach ($this->options as $option) {
            $long = str_pad($option->getLong() ?? '', $longest);
            $short = $option->getShort();
            if ($short !== null && $option->getLong() !== null) {
                $optionLines[] = sprintf(
                    ' -%s | --%s  %s',
                    $short,
                    $long,
                    $this->wrapText($option->getDescription(), $lineLength, $indent)
                );
            } elseif ($option->getLong() === null) {
                $optionLines[] = sprintf(
                    ' -%s     %s  %s',
                    $short ?? '',
                    $long,
                    $this->wrapText($option->getDescription(), $lineLength, $indent)
                );
            } else {
                $optionLines[] = sprintf(
                    '      --%s  %s',
                    $long,
                    $this->wrapText($option->getDescription(), $lineLength, $indent)
                );
            }
        }
        $output .= implode(PHP_EOL, $optionLines);
        if (!empty($this->arguments)) {
            $longest = 0;
            foreach ($this->arguments as $argument) {
                $length = strlen($argument->getName());
                $longest = $length > $longest ? $length : $longest;
            }
            $argumentLines = ['Arguments:'];
            foreach ($this->arguments as $argument) {
                $name = str_pad('<' . $argument->getName() . '>', $longest + 2);
                $argumentLines[] = sprintf(' %s  %s', $name, $argument->getDescription());
            }
            $output .= PHP_EOL . PHP_EOL . implode(PHP_EOL, $argumentLines);
        }
        return $output . PHP_EOL;
    }

    private function getoptShort(): string
    {
        $output = '';
        foreach ($this->options as $option) {
            $short = $option->getShort();
            if ($short !== null) {
                $output .= $short . $option->getSuffix();
            }
        }
        return $output;
    }

    private function getoptLong(): array
    {
        $output = [];
        foreach ($this->options as $option) {
            $long = $option->getLong();
            if ($long !== null) {
                $output[] = $long . $option->getSuffix();
            }
        }
        return $output;
    }

    private function wrapText(string $input, int $length, int $indent): string
    {
        $indentString = str_repeat(' ', $indent);
        $words = explode(' ', $input);
        $lines = [];
        $line = [];
        $lineLength = 0;
        foreach ($words as $word) {
            $wordLength = strlen($word);
            if ((count($line) - 1) + $lineLength + $wordLength > $length) {
                $lines[] = implode(' ', $line);
                $line = [$word];
                if ($indent > 0) {
                    array_unshift($line, $indentString);
                }
                $lineLength = $wordLength;
            } else {
                $line[] = $word;
                $lineLength += $wordLength;
            }
        }
        $lines[] = implode(' ', $line);
        return implode(PHP_EOL, $lines);
    }

    abstract protected function run(): string;
}
