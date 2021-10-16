<?php

/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Badge;

use Atanvarno\BuildTools\Command\Option;
use Exception;
use Atanvarno\BuildTools\Command\Argument;
use Atanvarno\BuildTools\Command\Command;
use SimpleXMLElement;

class Percent extends Command
{
    private const XPATH = '//metrics';

    protected int $percentage = 0;

    /**
     * @param string                     $name
     * @param array<array-key, Option>   $options
     * @param array<array-key, Argument> $arguments
     * @param string|null                $description
     */
    protected function __construct(
        string $name,
        array $options = [],
        array $arguments = [],
        ?string $description = null
    ) {
        parent::__construct($name, $options, $arguments, $description);
    }

    public static function create(): self
    {
        return new self(
            name:        'badge-percent-percent',
            options:     [],
            arguments:   [
                new Argument(name: 'file', description: 'Path to Clover XML file.')
            ],
            description: 'Tool to set Github action environment variable PERCENT to coverage percentage from Clover XML'
                         . ' file.',
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
        return sprintf('PERCENT=%d%%', $this->percentage);
    }

    /**
     * @throws Exception
     */
    protected function calculatePercentage(): void
    {
        $filename = $this->arguments[0]->getValue();
        if ($filename === null) {
            throw new Exception('No <file> argument given');
        }
        if (!file_exists($filename)) {
            throw new Exception(sprintf('Can not find %s', $filename));
        }
        $xml = new SimpleXMLElement(file_get_contents($filename));
        $metrics = $xml->xpath(self::XPATH);
        $totalElements = 0;
        $checkedElements = 0;
        foreach ($metrics as $metric) {
            $totalElements += (int)$metric['elements'];
            $checkedElements += (int)$metric['coveredelements'];
        }
        $this->percentage = (int) ($checkedElements / $totalElements * 100);
    }
}
