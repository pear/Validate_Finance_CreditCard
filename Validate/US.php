<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * Specific validation methods for data used in the United States
 *
 * PHP versions 4
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Validate
 * @package    Validate_US
 * @author     Brent Cook <busterbcook@yahoo.com>
 * @author     Tim Gallagher <timg@sunflowerroad.com>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_US
 */

/**
 * Data validation class for the United States
 *
 * This class provides methods to validate:
 *  - Social insurance number (aka SSN)
 *  - Region (state code)
 *  - Postal code
 *  - Telephone number
 *
 * @category   Validate
 * @package    Validate_US
 * @author     Brent Cook <busterbcook@yahoo.com>
 * @author     Tim Gallagher <timg@sunflowerroad.com>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Validate_US
 */
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
        if (!is_numeric($ssn) || strlen($ssn) != 9) {
            return false;
        }
        $area   = substr($ssn, 0, 3);
        $group  = intval(substr($ssn, 3, 2));
        $serial = intval(substr($ssn, 5, 4));

        if (!$high_groups) {
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
        if (is_array($groupNumber)) {
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
    function ssnCheck($area, $group, $serial, $high_groups)
    {
        if (is_array($area)) {
            extract($area);
        }
        // perform trivial checks
        // no field should contain all zeros
        if (!($area && $group && $serial)) {
            return false;
        }

        // check if this area has been assigned yet
        if (!isset($high_groups[$area])) {
            return false;
        }

        $high_group = $high_groups[$area];

        $high_group_range = Validate_US::ssnGroupRange($high_group);
        $group_range = Validate_US::ssnGroupRange($group);

        // if the assigned range is higher than this group number, we're OK
        if ($high_group_range > $group_range) {
            return true;
        } elseif ($high_group_range < $group_range) {
            // if the assigned range is lower than the group number, that's bad
            return false;
        } elseif ($high_group >= $group) {
            // we must be in the same range, check the actual numbers
            return true;
        }

        return false;
    }

    /**
     * Gets the most current list the highest SSN group numbers issued
     * from the Social Security Administration website. This info can be
     * cached for performance (and to lessen the load on the SSA website)
     *
     * @param string $uri Path to the SSA highgroup.htm file
     * @param bool   $is_text Take the $uri param as directly the contents
     * @returns array
     */
    function ssnGetHighGroups($uri = 'http://www.ssa.gov/employer/highgroup.txt',
                              $is_text = false)
    {
        /**
         * Stores high groups that have been fetched from any given web page to
         * keep the load down if having to validate more then one ssn in a row
         */
        static $high_groups = array();
        static $lastUri = '';

        if ($lastUri == $uri && !empty($high_groups)) {
            return $high_groups;
        }
        $lastUri = $uri;

        if ($is_text) {
            $source = $uri;
        } else {
            if (!$fd = @fopen($uri, 'r')) {
                trigger_error('Could not access the SSA High Groups file', E_USER_WARNING);
                return array();
            }
            $source = '';
            while ($data = fread($fd, 2048)) {
                $source .= $data;
            }
            fclose($fd);
        }

        $lines =  explode("\n", ereg_replace("[^\n0-9]*", '', $source));
        $high_groups = array();
        foreach ($lines as $line) {
            if (ereg('^([0-9]{3})([0-9]{2})([0-9]{3})([0-9]{2})([0-9]{3})([0-9]{2})([0-9]{3})([0-9]{2})([0-9]{3})([0-9]{2})([0-9]{3})([0-9]{2})$', $line, $grouping)) {
                $high_groups[$grouping[1]] =  $grouping[2];
                $high_groups[$grouping[3]] =  $grouping[4];
                $high_groups[$grouping[5]] =  $grouping[6];
                $high_groups[$grouping[7]] =  $grouping[8];
                $high_groups[$grouping[9]] =  $grouping[10];
                $high_groups[$grouping[11]] =  $grouping[12];
            }
        }
        $this->high_groups = $high_groups;
        return $high_groups;
    }

    /**
     * Validates a US Postal Code format (ZIP code)
     *
     * @param string $postalCode the ZIP code to validate
     * @param   bool    optional; strong checks (e.g. against a list of postcodes) (not implanted)
     * @return boolean TRUE if code is valid, FALSE otherwise
     * @access public
     * @static
     * @todo Integrate with USPS web API
     */
    function postalCode($postalCode, $strong = false)
    {
        return (bool)preg_match('/^[0-9]{5}((-| )[0-9]{4})?$/', $postalCode);
    }

    /**
     * Validates a "region" (i.e. state) code
     *
     * @param string $region 2-letter state code
     * @return bool Whether the code is a valid state
     * @static
     */
    function region($region)
    {
        switch (strtoupper($region)) {
            case 'AL':
            case 'AK':
            case 'AZ':
            case 'AR':
            case 'CA':
            case 'CO':
            case 'CT':
            case 'DE':
            case 'DC':
            case 'FL':
            case 'GA':
            case 'HI':
            case 'ID':
            case 'IL':
            case 'IN':
            case 'IA':
            case 'KS':
            case 'KY':
            case 'LA':
            case 'ME':
            case 'MD':
            case 'MA':
            case 'MI':
            case 'MN':
            case 'MS':
            case 'MO':
            case 'MT':
            case 'NE':
            case 'NV':
            case 'NH':
            case 'NJ':
            case 'NM':
            case 'NY':
            case 'NC':
            case 'ND':
            case 'OH':
            case 'OK':
            case 'OR':
            case 'PA':
            case 'RI':
            case 'SC':
            case 'SD':
            case 'TN':
            case 'TX':
            case 'UT':
            case 'VT':
            case 'VA':
            case 'WA':
            case 'WV':
            case 'WI':
            case 'WY':
                return true;
        }
        return false;
    }

    /**
     * Validate a US phone number.
     *
     * Can allow only seven digit numbers.
     * Also allows the formats, (xxx) xxx-xxxx, xxx xxx-xxxx and now 0-1 x xxx xxx-xxxx.,
     * or various combination without spaces or dashes.
     * THIS SHOULD EVENTUALLY take a FORMAT in the options, instead
     *
     * @param  string    $number             phone to validate
     * @param  bool      $requireAreaCode    require the area code? (default: true)
     * @return bool                          The valid or invalid phone number
     */
    function phoneNumber($number, $requireAreaCode = true)
    {
        if ($number == '') {
            return true;
        }

        if (!$requireAreaCode) {
            // just seven digits, maybe a space or dash
            if (preg_match('/^[2-9]\d{2}[- ]?\d{4}$/', $number)) {
                return  true;
            }
        } else {
            // ten digits, maybe  spaces and/or dashes and/or parentheses maybe a 1 or a 0..
            if (preg_match('/^[0-1]?[- ]?(\()?[2-9]\d{2}(?(1)\))[- ]?[2-9]\d{2}[- ]?\d{4}$/',
                           $number)) {
            	return true;
            }
        }
        return false;
    }



}
?>
