<?php

/*
 * This file is part of the octris/octris.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octris\Util;

/**
 * Helper methods for various validations.
 *
 * @copyright   copyright (c) 2016 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Validator
{
    /**
     * Validate if something seems to be a valid project path.
     * 
     * @param   string              $value                  Value to test.
     * @return  bool                                        Returns true or false.
     */
    public static function isProjectPath($value)
    {
        if (!($return = is_dir($value))) {
            fwrite(STDERR, "Specified path is not a directory or directory not found\n");
        } elseif (!($return = is_file($value . '/etc/global.php'))) {
            fwrite(STDERR, sprintf("global app configuration not found \"%s\"!\n", $value . '/etc/global.php'));
        }
        
        return $return;
    }
}
