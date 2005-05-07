<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * Specific validation methods for data used in Austria
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
 * @package    Validate_AT
 * @author     Michael Wallner <mike@iworks.at>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
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
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
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