<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Brent Cook <busterb@mail.utexas.edu>                        |
// |          Tim Gallagher <timg@sunflowerroad.com>                      |
// +----------------------------------------------------------------------+
//
// $Id$
//
// Specific validation methods for data used in the United States
//

require_once 'PEAR.php';
require_once 'File.php';

class Validate_US
{
    /**
     * Validates a social security number
     * @param string $ssn number to validate
     * @param array $high_groups array of highest issued SSN group numbers
     * @returns bool
     */
    function ssn($ssn, $high_groups = null)
    {
        // remove any dashes, spaces, returns, tabs or slashes
        $ssn = str_replace(array('-','/',' ',"\t","\n"), '', $ssn);

        // check if this is a 9-digit number
        if (!is_numeric($ssn) || !(strlen($ssn) == 9)) {
            return false;
        }
        $area   = intval(substr($ssn, 0, 3));
        $group  = intval(substr($ssn, 3, 2));
        $serial = intval(substr($ssn, 5, 4));

        if (is_null($high_groups)) {
            $high_groups = Validate_US::ssnGetHighGroups();
        }
        return Validate_US::ssnCheck($area, $group, $serial, $high_groups);
    }

    /**
    * Returns a range for a supplied group number, which
    * is the middle, two-digit field of a SSN.
    * Group numbers are defined as follows:
    * 1 - Odd numbers, 01 to 09
    * 2 - Even numbers, 10 to 98
    * 3 - Even numbers, 02 to 08
    * 4 - Odd numbers, 11 to 99
    * @param int $groupNumber a group number to check, 00-99
    * @return int
    */
    function ssnGroupRange($groupNumber)
    {
        if(is_array($groupNumber)){
            extract($groupNumber);
        }
        if ($groupNumber < 10) {
            // is the number odd?
            if ($groupNumber % 2) {
                return 1;
            } else {
                return 3;
            }
        } else {
            // is the number odd?
            if ($groupNumber % 2) {
                return 4;
            } else {
                return 2;
            }
        }
    }

    /**
     * checks if a Social Security Number is valid
     * needs the first three digits and first two digits and the
     * final four digits as separate integer parameters
     * @param int $area 3-digit group in a SSN
     * @param int $group 2-digit group in a SSN
     * @param int $serial 4-digit group in a SSN
     * @param array $high_groups array of highest issued group numbers
     *                           area number=>group number
     */
    function ssnCheck($ssnCheck, $group, $serial, $high_groups)
    {
        if(is_array($ssnCheck)){
            extract($ssnCheck);
        }
        // perform trivial checks
        // no field should contain all zeros
        if (!($area && $group && $serial)) {
            return false;
        }

        // check if this area has been assigned yet
        if (!($high_group = $high_groups[$area])) {
            return false;
        }

        $high_group_range = Validate_US::ssnGroupRange($high_group);
        $group_range = Validate_US::ssnGroupRange($group);

        // if the assigned range is higher than this group number, we're OK
        if ($high_group_range > $group_range) {
            return true;
        } else {
            // if the assigned range is lower than the group number, that's bad
            if ($high_group_range < $group_range) {
                return false;
            } else {
                // we must be in the same range, check the actual numbers
                if ($high_group >= $group) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Gets the most current list the highest SSN group numbers issued
     * from the Social Security Administration website. This info can be
     * cached for performance (and to lessen the load on the SSA website)
     *
     * @param string $uri Path to the SSA highgroup.htm file
     * @param bool   $is_text Take the $highgroup_htm param as directly the contents
     * @returns array
     */
    function ssnGetHighGroups($uri = 'http://www.ssa.gov/foia/highgroup.htm',
                              $is_text = false)
    {
        if (!$is_text) {
            if (!$fd = @fopen($uri, 'r')) {
                trigger_error("Could not access the SSA High Groups file", E_USER_WARNING);
                return array();
            }
            $source = '';
            while ($data = fread($fd, 2048)) {
                $source .= $data;
            }
            fclose($fd);
        }

        $search = array ("'<script[^>]*?>.*?</script>'si",  // Strip javascript
                         "'<[\/\!]*?[^<>]*?>'si",           // Strip html tags
                         "'([\r\n])[\s]+'",                 // Strip white space
                         "'\*'si");

        $replace = array ('','','\\1','');

        $lines = explode("\n", preg_replace($search, $replace, $source));
        $high_groups = array();
        foreach ($lines as $line) {
            $line = trim($line);
            if ((strlen($line) == 3) && is_numeric($line)) {
                $current_group = $line;
            } elseif ((strlen($line) == 2) && is_numeric($line)) {
                $high_groups[$current_group] = $line;
            }
        }
        return $high_groups;
    }
}
?>