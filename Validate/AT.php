<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005 Michael Wallner                              |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
// | Author: Michael Wallner <mike@iworks.at>                             |
// +----------------------------------------------------------------------+
//
/**
 * Specific validation methods for data used in Austria
 *
 * @category   Validate
 * @package    Validate_AT
 * @author     Michael Wallner <mike@iworks.at>
 * @copyright  1997-2005 Michael Wallner
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_AT
 */

/**
* Requires Validate
*/
require_once 'Validate.php';

/**
 * Data validation class for Austria
 *
 * This class provides methods to validate:
 *  - Social insurance number (aka SSN)
 *  - Postal code
 *
 * @category   Validate
 * @package    Validate_AT
 * @author     Michael Wallner <mike@php.net>
 * @copyright  1997-2005 Michael Wallner
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Validate_AT
 */
class Validate_AT
{
    /**
    * Validate postcode ("Postleitzahl")
    *
    * @static
    * @access   public
    * @param    string  postcode to validate
    * @param    bool    optional; strong checks (e.g. against a list of postcodes)
    * @return   bool    true if postcode is ok, false otherwise
    */
    function postalCode($postcode, $strong = false)
    {
        if ($strong) {
            static $postcodes;

            if (!isset($postcodes)) {
                $file = '@DATADIR@/Validate_AT/AT_postcodes.txt';
                $postcodes = array_map('trim', file($file));
            }

            return in_array((int)$postcode, $postcodes);
        }
        return (bool)ereg('^[0-9]{4}$', $postcode);
    }

    /**
    * Validate SSN
    *
    * "Sozialversicherungsnummer"
    *
    * @static
    * @access   public
    * @param    string  $svn
    * @return   bool
    */
    function ssn($svn)
    {
        $matched = preg_match(
            '/^(\d{3})(\d)\D*(\d{2})\D*(\d{2})\D*(\d{2})$/',
            $svn,
            $matches
        );

        if (!$matched) {
            return false;
        }

        list(, $num, $chk, $d, $m, $y) = $matches;

        if (!Validate::date("$d-$m-$y", array('format' => '%d-%m-%y'))) {
            return false;
        }

        $str = (string) $num . $chk . $d . $m . $y;
        $len = strlen($str);
        $fkt = '3790584216';
        $sum = 0;

        for ($i = 0; $i < $len; $i++) {
            $sum += $str{$i} * $fkt{$i};
        }

        $sum = $sum % 11;
        if ($sum == 10) {
            $sum = 0;
        }

        return ($sum == $chk);
    }
}
?>