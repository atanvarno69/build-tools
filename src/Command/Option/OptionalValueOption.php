<?php

/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Command\Option;

use Atanvarno\BuildTools\Command\Option;

class OptionalValueOption implements Option
{
    use ValueOptionTrait;

    public function getSuffix(): string
    {
        return '::';
    }
}
