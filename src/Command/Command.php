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
     * @param array $input Use $argv.
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
            foreach($arguments as $value) {
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
            $output .= sprintf('%s%s%s', $this->description, PHP_EOL, PHP_EOL);
        }
        $argumentNames = [];
        foreach ($this->arguments as $argument) {
            $argumentNames[] = sprintf('<%s>', $argument->getName());
        }
        $argumentNames = implode(' ', $argumentNames);
        $output .= sprintf('Usage: %s [options] %s%s%s', $this->name, $argumentNames, PHP_EOL, PHP_EOL);
        $longest = 0;
        foreach($this->options as $option) {
            if($option->getLong() !== null) {
                $length = strlen($option->getLong());
                $longest = $length > $longest ? $length : $longest;
            }
        }
        $optionLines = ['Options:'];
        foreach ($this->options as $option) {
            $long = str_pad($option->getLong() ?? '', $longest);
            if ($option->getShort() !== null && $option->getLong() !== null) {
                $optionLines[] = sprintf(' -%s | --%s  %s', $option->getShort(), $long, $option->getDescription());
            } elseif ($option->getLong() === null) {
                $optionLines[] = sprintf(' -%s     %s  %s', $option->getShort(), $long, $option->getDescription());
            } else {
                $optionLines[] = sprintf('      --%s  %s', $long, $option->getDescription());
            }
        }
        $output .= implode(PHP_EOL, $optionLines);
        if (!empty($this->arguments)) {
            $longest = 0;
            foreach ($this->arguments as $argument) {
                if ($argument->getName() !== null) {
                    $length = strlen($argument->getName());
                    $longest = $length > $longest ? $length : $longest;
                }
            }
            $argumentLines = ['Arguments:'];
            foreach($this->arguments as $argument) {
                $name = str_pad('<' . $argument->getName() ?? '' . '>', $longest);
                $argumentLines[] = sprintf(' %s  %s', $name, $argument->getDescription() ?? '');
            }
            $output .= implode(PHP_EOL, $argumentLines);
        }
        return $output . PHP_EOL;
    }

    private function getoptShort(): string
    {
        $output = '';
        foreach ($this->options as $option) {
            if ($option->getShort() !== null) {
                $output .= $option->getShort() . $option->getSuffix();
            }
        }
        return $output;
    }

    private function getoptLong(): array
    {
        $output = [];
        foreach ($this->options as $option) {
            if ($option->getLong() !== null) {
                $output[] = $option->getLong() . $option->getSuffix();
            }
        }
        return $output;
    }

    abstract protected function run(): string;
}
