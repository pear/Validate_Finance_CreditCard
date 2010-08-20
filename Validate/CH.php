<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Data validation class for Switzerland
 *
 * PHP Versions 4 and 5
 *
 * This source file is subject to the New BSD license, That is bundled
 * with this package in the file LICENSE, and is available through
 * the world-wide-web at
 * http://www.opensource.org/licenses/bsd-license.php
 * If you did not receive a copy of the new BSDlicense and are unable
 * to obtain it through the world-wide-web, please send a note to
 * pajoye@php.net so we can mail you a copy immediately.
 *
 * @category  Validate
 * @package   Validate_CH
 * @author    Hans-Peter Oeri <hp@oeri.ch>
 * @copyright 1997-2005 Hans-Peter Oeri
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_CH
 */

/**
* Requires base class Validate
*/
require_once 'Validate.php';

/**
 * Data validation class for Switzerland
 *
 * This class provides methods to validate:
 *  - Social insurance number (aka SSN)
 *  - Swiss university's immatriculation number
 *  - Postal code
 *
 * @category  Validate
 * @package   Validate_CH
 * @author    Hans-Peter Oeri <hp@oeri.ch>
 * @copyright 1997-2005 Hans-Peter Oeri
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Validate_CH
 */
class Validate_CH
{
    /**
    * Validate a Swiss social security number
    *
    * The Swiss social security numbers follow a very strict format.
    * A check digit is the last one, computed the standard
    * _get_control_number function.
    *
    * @param string $ssn ssn to validate
    *
    * @static
    * @access   public
    * @return   bool    true on success
    */
    function ssn($ssn)
    {
        $t_regex = preg_match('/\d{3}\.\d{2}\.\d{3}\.\d{3}/', $ssn, $matches);

        if (!$t_regex) {
            return false;
        }

        // weight 0 to non digits -> ignored
        $weights = array(5, 4, 3, 0, 2, 7, 0, 6, 5, 4, 0, 3, 2);

        // although theoretically, a check "digit" of 10 could result,
        // no such ssn is issued! allow_high is therefore meaningless...
        $t_check = Validate::_checkControlNumber($ssn, $weights, 11, 11);

        return $t_check;
    }

    /**
    * Validate a Swiss university's immatriculation number
    *
    * Swiss immatriculation numbers are used by all universities
    * in Switzerland. They are used in two primary formats: Official
    * is a readable one with dashes, but commonly only the eight
    * digits are just concatenated.
    *
    * As for check digit, a somewhat "weird" algorithm is used, in
    * which not a sum of products, but the sum of the digits of products
    * are taken.
    *
    * @param string $umn university's immatriculation number
    *
    * @static
    * @access   public
    * @return   bool    true on success
    */
    function studentid($umn)
    {
        // we accept both formats
        $umn     = preg_replace('/(\d{2})-(\d{3})-(\d{3})/', '$1$2$3', $umn);
        $t_regex = preg_match('/\d{8}/', $umn);

        if (!$t_regex) {
            return false;
        }

        // NOW, we have to go on ourselves, as not the products
        // but the digits of the products are to be added!

        $weights = array(2, 1, 2, 1, 2, 1, 2);

        $sum = 0;

        for ($i = 0; $i <= 6; ++$i) {
            $tsum =  $umn{$i} * $weights[$i];
            $sum += ($tsum > 9 ? $tsum - 9 : $tsum);
        }

        $sum = 10 - $sum%10;

        return ($sum == $umn[7]);
    }

    /**
    * Validate a Swiss ZIP
    *
    * @param string $postcode postcode to validate
    * @param bool   $strong   optional; strong checks (e.g. against a 
    *                         list of postcodes)
    *
    * @static
    * @access   public
    * @return   bool    true if postcode is ok, false otherwise
    */
    function postalCode($postcode, $strong = false)
    {
        if ($strong) {
            static $postcodes;

            if (!isset($postcodes)) {
                $datadir = dirname(dirname(__FILE__));
                
                $paths = array('@DATADIR@/Validate_CH/CH_postcodes.txt', 
                               $datadir . '/data/CH_postcodes.txt');

                foreach ($paths as $file) {
                    if (file_exists($file)) {
                        $postcodes = array_map('trim', file($file));
                    }
                }
            }

            return in_array($postcode, $postcodes);
        }
        return (bool) preg_match('/^[0-9]{4}$/', $postcode);
    }
}
?>
