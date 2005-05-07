<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * Specific validation methods for data used in South Africa
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
 * @package    Validate_ZA
 * @author     Jacques Marneweck <jacques@php.net>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_ZA
 */

/**
* Requires base class Validate
*/
require_once 'Validate.php';

/**
 * Data validation class for South Africa
 *
 * This class provides methods to validate:
 *  - Social insurance number (aka SSN)
 *  - Province code
 *  - Postal code
 *
 * @category   Validate
 * @package    Validate_ZA
 * @author     Jacques Marneweck <jacques@php.net>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Validate_ZA
 */
class Validate_ZA
{
    /**
     * Validate a South African Postal Code
     *
     * I've downloaded a list of postal codes from the SAPO website and
     * reduced the list down to unique postal codes.
     *
     * @static
     * @access  public
     * @param   string  the postal code to validate
     * @param   bool    optional; strong checks (e.g. against a list of postcodes)
     * @return  bool    true if postal code is ok else false
     * @link    http://www.sapo.co.za/cms/download/postcodes.zip
     */
    function postalCode($postcode, $strong = false)
    {
        if (!is_numeric($postcode)) {
            return false;
        }

        if ($strong) {
            static $postcodes;

            if (!isset($postcodes)) {
                $file = '@DATADIR@/Validate_ZA/ZA_postcodes.txt';
                $postcodes = array_map('trim', file($file));
            }
            return in_array((int)$postcode, $postcodes);
        }
        return (bool)ereg('^[0-9]{4}$', $postcode);
    }

    /**
     * Validates a "region" (i.e. province) code
     *
     * @static
     * @param   string  2-letter province code
     * @return  bool    true if valid else false
     * @access  public
     */
    function region($region)
    {
        switch (strtoupper($region)) {
            case 'EC': /* Eastern Cape */
            case 'FS': /* Free State */
            case 'GP': /* Gauteng */
            case 'KN': /* Kwa-Zulu Natal */
            case 'MP': /* Mpumalanga */
            case 'NC': /* Northern Cape */
            case 'NP': /* Limpopo (former Northern Province) */
            case 'NW': /* North West */
            case 'WC': /* Western Cape */
                return true;
                break;
            default:
                return false;
        }
        return (false);
    }                                                                              

    /**
     * Validate a South African ID Number
     *
     * @param   string  11 digit South African Identity Number
     * @return  bool    true if valid else false
     * @access  public
     */
    function ssn($id)
    {
        $match = preg_match ("!^(\d{2})(\d{2})(\d{2})[0|5]\d{6}$!", $id, $matches);
        if (!$match) {
            return false;
        }

        list (, $year, $month, $day) = $matches;

        /**
         * Check that the date is valid
         */
        if (!Validate::date("$year-$month-$day", array('format' => '%y-%m-%d'))) {
            return false;
        }
        
        require_once 'Validate/Finance/CreditCard.php';

        if (Validate_Finance_CreditCard::Luhn($id)) {
            return true;
        }
        return false;
    }
}
?>
