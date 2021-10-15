<?php

/**
 * @package   atanvarno/build-tools
 * @copyright 2021 atanvarno.com
 * @license   https://opensource.org/licenses/MIT The MIT License
 * @author    atanvarno69 <https://github.com/atanvarno69>
 */

declare(strict_types = 1);

namespace Atanvarno\BuildTools\Functions;

use Exception;

if (!function_exists('findAutoloader')) {
    /**
     * @throws Exception
     */
    function findAutoloader(): string
    {
        $locations = [
            // root/vendor/atanvarno/build-tools/src/Functions/FindAutoloader.php
            dirname(__FILE__, 5) . '/autoload.php',
            // root/src/Functions/FindAutoloader.php
            dirname(__FILE__, 3) . '/vendor/autoload.php',
        ];
        foreach ($locations as $filename) {
            if (file_exists($filename)) {
                return $filename;
            }
        }
        throw new Exception('Can not find autoloader');
    }
}
