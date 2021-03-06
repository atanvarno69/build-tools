<?php

/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Badge;

use Atanvarno\BuildTools\Command\Argument;
use Atanvarno\BuildTools\Command\Option;
use Atanvarno\BuildTools\Command\Option\OptionalValueOption;
use Exception;

class Color extends Percent
{
    /**
     * @param string                     $name
     * @param array<array-key, Option>   $options
     * @param array<array-key, Argument> $arguments
     * @param string|null                $description
     */
    private function __construct(string $name, array $options = [], array $arguments = [], ?string $description = null)
    {
        parent::__construct($name, $options, $arguments, $description);
    }

    public static function create(): self
    {
        return new self(
            name:        'badge-percent-color',
            options:     [
                new OptionalValueOption(
                    long: 'upper',
                    short: 'u',
                    description: 'Set the upper threshold for percent coverage, below which the color will be '
                          . '\'important\'. Default 95.'
                ),
                new OptionalValueOption(
                    long: 'lower',
                    short: 'u',
                    description: 'Set the lower threshold for percent coverage, below which the color will be'
                          . ' \'critical\'. Default 80.'
                ),
            ],
            arguments:   [new Argument(name: 'file', description: 'Path to Clover XML file.')],
            description: 'Tool to set Github action environment variable COLOR to from coverage percentage from Clover '
                         . 'XML file.',
        );
    }

    /**
     * @throws Exception
     */
    protected function run(): string
    {
        if ($this->options['help']->isSet()) {
            return $this->getHelpText();
        }
        $this->calculatePercentage();
        $upper = (int) ($this->options['upper']->getValue() ?? 95);
        $lower = (int) ($this->options['lower']->getValue() ?? 80);
        if ($this->percentage >= $upper) {
            $color = 'success';
        } elseif ($this->percentage >= $lower) {
            $color = 'important';
        } else {
            $color = 'critical';
        }
        return sprintf('COLOR=%s', $color);
    }
}
