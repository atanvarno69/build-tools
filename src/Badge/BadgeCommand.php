<?php
/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Badge;

use Exception;
use SimpleXMLElement;

/**
 * Atanvarno\BuildTools\Badge\BadgeCommand
 *
 *
 */
class BadgeCommand
{
    private const OPTIONS_SHORT = 'chl:pu:';
    private const OPTIONS_LONG = [
        'color',
        'help',
        'lower:',
        'percent',
        'upper:',
    ];
    private const STATUS_SUCCESS = 0;
    private const HELP_TEXT = <<<HELP
        Usage: badge [options] <file>

        -c     | --color      Print 'COLOR=<color>'
        -h     | --help       Print this help message
        -l <n> | --lower <n>  Sets the lower threshold for percent coverage, below which
                              the color will be 'critical'. Default 80.
        -p     | --percent    Print 'PERCENT=<value>%'
        -u     | --upper      Sets the upper threshold for percent coverage, below which
                              the color will be 'important'. Default 95.

        <file>  Required. Clover coverage XML file to parse.
        HELP;
    private const XPATH = '//metrics';

    private bool $displayColor = false, $displayHelp, $displayPercent = false;
    private int $max, $min;
    private string $cloverFile;

    /**
     * @throws Exception Clover file not given or can't be found.
     */
    public function __construct(array $arguments)
    {
        $options = getopt(self::OPTIONS_SHORT, self::OPTIONS_LONG, $index);
        $this->displayHelp = isset($options['h']) || isset($options['help']);
        if ($this->displayHelp) {
            return;
        }
        $this->displayPercent = isset($options['p']) || isset($options['percent']);
        $this->displayColor = isset($options['c']) || isset($options['color']);
        if ($this->displayColor) {
            if (isset($options['lower'])) {
                $this->min = (int) $options['lower'];
            } elseif (isset($options['l'])) {
                $this->min = (int) $options['l'];
            } else {
                $this->min = 80;
            }
            if (isset($options['upper'])) {
                $this->max = (int) $options['upper'];
            } elseif (isset($options['u'])) {
                $this->max = (int) $options['u'];
            } else {
                $this->max = 95;
            }
        }
        if ($this->displayColor || $this->displayPercent) {
            $arguments = array_slice($arguments, $index);
            if (empty($arguments)) {
                throw new Exception('Path to Clover coverage log must be given');
            }
            if (!file_exists($arguments[0])) {
                throw new Exception(sprintf('Can not find %s', $arguments[0]));
            }
            $this->cloverFile = $arguments[0];
        }
    }

    /**
     * @throws Exception XML couldn't be parsed.
     */
    public function execute(): void
    {
        if ($this->displayHelp) {
            $this->printHelp();
            return;
        }
        if ($this->displayColor && !$this->displayPercent) {
            $this->printColor();
            return;
        }
        if ($this->displayPercent) {
            $this->printPercent();
            return;
        }
        $this->printFallback();
    }

    /**
     * @throws Exception XML couldn't be parsed.
     */
    private function calculatePercent(): int
    {
        $xml = new SimpleXMLElement(file_get_contents($this->cloverFile));
        $metrics = $xml->xpath(self::XPATH);
        $totalElements = 0;
        $checkedElements = 0;
        foreach ($metrics as $metric) {
            $totalElements += (int)$metric['elements'];
            $checkedElements += (int)$metric['coveredelements'];
        }
        return (int) ($checkedElements / $totalElements * 100);
    }

    /**
     * @throws Exception XML couldn't be parsed.
     */
    private function printColor(): void
    {
        $percent = $this->calculatePercent();
        if ($percent >= $this->max) {
            $color = 'success';
        } elseif ($percent >= $this->min) {
            $color = 'important';
        } else {
            $color = 'critical';
        }
        echo sprintf('COLOR=%s', $color);
    }

    private function printFallback(): void
    {
        echo sprintf('No valid options given, displaying help text:%s', PHP_EOL);
        $this->printHelp();
    }

    private function printHelp(): void
    {
        echo sprintf('%s%s', self::HELP_TEXT, PHP_EOL);
    }

    /**
     * @throws Exception XML couldn't be parsed.
     */
    private function printPercent(): void
    {
        echo sprintf('PERCENT=%d%%', $this->calculatePercent());
    }
}
