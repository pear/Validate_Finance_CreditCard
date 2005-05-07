<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * Methods for common data validations
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
 * @package    Validate_CH
 * @author     Hans-Peter Oeri <hp@oeri.ch>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_CH
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
 * @category   Validate
 * @package    Validate_CH
 * @author     Hans-Peter Oeri <hp@oeri.ch>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Validate_CH
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
    * @static
    * @access   public
    * @param    string  ssn to validate
    * @return   bool    true on success
    */
    function ssn($ssn)
    {
        $t_regex = preg_match('/\d{3}\.\d{2}\.\d{3}\.\d{3}/', $ssn, $matches );
        
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
    * @static
    * @access   public
    * @param    string  immatriculation number 
    * @return   bool    true on success
    */
    function studentid($umn)
    {
        // we accept both formats
        $umn = preg_replace('/(\d{2})-(\d{3})-(\d{3})/', '$1$2$3', $umn); 
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
                $file = '@DATADIR@/Validate_CH/CH_postcodes.txt';
                $postcodes = array_map('trim', file($file));
            }
    
            return in_array($postcode, $postcodes);
        }
        return (bool)ereg('^[0-9]{4}$', $postcode);
    } 
}
?>
