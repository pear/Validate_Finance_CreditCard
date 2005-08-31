<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * Specific validation methods for data used in Canada
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Validate
 * @package    Validate_CA
 * @author     Philippe Jausions <Philippe.Jausions@11abacus.com>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_CA
 */

/**
 * Data validation class for Canada
 *
 * This class provides methods to validate:
 *  - Social insurance number (aka SIN)
 *  - Province code
 *  - Telephone number
 *  - Postal code
 *
 * @category   Validate
 * @package    Validate_CA
 * @author     Philippe Jausions <Philippe.Jausions@11abacus.com>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Validate_CA
 */
class Validate_CA
{
    /**
     * Validates a number according to Luhn check algorithm
     *
     * This function checks given number according Luhn check
     * algorithm. It is published on several places, also here:
     *
     * @link http://www.webopedia.com/TERM/L/Luhn_formula.html
     * @link http://www.merriampark.com/anatomycc.htm
     * @link http://hysteria.sk/prielom/prielom-12.html#3 (Slovak language)
     * @link http://www.speech.cs.cmu.edu/~sburke/pub/luhn_lib.html (Perl lib)
     *
     * @param  string  $number to check
     * @return bool    TRUE if number is valid, FALSE otherwise
     * @access public
     * @static
     * @author Ondrej Jombik <nepto@pobox.sk>
     */
    function Luhn($number)
    {
        $len_number = strlen($number);
        $sum = 0;
        for ($k = $len_number % 2; $k < $len_number; $k += 2) {
            if ((intval($number{$k}) * 2) > 9) {
                $sum += (intval($number{$k}) * 2) - 9;
            } else {
                $sum += intval($number{$k}) * 2;
            }
        }
        for ($k = ($len_number % 2) ^ 1; $k < $len_number; $k += 2) {
            $sum += intval($number{$k});
        }
        return ($sum % 10) ? false : true;
    }

    /**
     * Validates a Canadian social insurance number (SIN)
     *
     * For unification between country-based validation packages,
     * this method is named ssn()
     *
     * @param string $ssn number to validate
     * @param int    $expiry_date expiry date for SIN starting with a 9
     *               (UNIX timestamp)
     * @returns bool
     * @link http://www.hrsdc.gc.ca/en/hip/lld/cesg/promotersection/files/Interface_Transaction_Standards_V301_English.pdf
     */
    function ssn($ssn, $expiryDate = null)
    {
        // remove any dashes, spaces, returns, tabs or slashes
        $ssn = str_replace(array('-','/',' ',"\t"), '', trim($ssn));

        // Basic checking
        if (($len = strlen($ssn)) != 9
            || strspn($ssn, '0123456789') != $len
            || ($ssn{0} == '9' && $expiryDate <= time())
            || $ssn{0} == '0' || $ssn{0} == '3' || $ssn{0} == '8') {
            return false;
        }

        return Validate_CA::Luhn($ssn);
    }

    /**
     * Validates a Canadian Postal Code
     *
     * @param string  $postalCode the postal code to validate
     * @param string  $province against a the province code
     * @return boolean TRUE if code is valid, FALSE otherwise
     * @access public
     * @static
     * @link http://www.canadapost.ca/business/tools/pg/preparation/mpp2-04-e.asp#c154
     */
    function postalCode($postalCode, $province = '')
    {
        $letters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        if (!$province) {
            $sRegExp = '[ABCEGHJKLMNPRSTVXY][0-9][' . $letters . '][ \t-]*[0-9][' . $letters . '][0-9]';

        } else {
            switch (strtoupper($province)) {
                case 'NF':          // Newfoundland
                    $sRegExp = 'A';
                    break;
                case 'NS':          // Nova Scotia
                    $sRegExp = 'B';
                    break;
                case 'PE':          // Prince Edward Island
                    $sRegExp = 'C';
                    break;
                case 'NB':          // New Brunswick
                    $sRegExp = 'E';
                    break;
                case 'QC':          // Quebec
                    $sRegExp = '[GHJ]';
                    break;
                case 'ON':          // Ontario
                    $sRegExp = '[KLMNP]';
                    break;
                case 'MB':          // Manitoba
                    $sRegExp = 'R';
                    break;
                case 'SK':          // Saskatchewan
                    $sRegExp = 'S';
                    break;
                case 'AB':          // Alberta
                    $sRegExp = 'T';
                    break;
                case 'BC':          // British Columbia
                    $sRegExp = 'V';
                    break;
                case 'NT':          // Northwest Territories
                case 'NU':          // Nunavut
                    $sRegExp = 'X';
                    break;
                case 'YK':          // Yukon Territory
                    $sRegExp = 'Y';
                    break;
                default:
                    return false;
            }

            $sRegExp .= '[0-9][' . $letters . '][ \t-]*[0-9][ ' . $letters . '][0-9]';
        }

        $sRegExp = '/^' . $sRegExp . '$/';

        return (bool) preg_match($sRegExp, strtoupper($postalCode));
    }

    /**
     * Validates a "region" (i.e. province) code
     *
     * @param string $region 2-letter province code
     * @return bool Whether the code is a valid province
     * @access public
     * @static
     */
    function region($region)
    {
        switch (strtoupper($region)) {
            case 'AB':
            case 'BC':
            case 'MB':
            case 'NB':
            case 'NF':
            case 'NT':
            case 'NS':
            case 'NU':
            case 'ON':
            case 'PE':
            case 'QC':
            case 'SK':
            case 'YK':
                return true;
        }
        return false;
    }

    /**
     * Validates a Canadian phone number.
     *
     * Canada and the United States share the same numbering plan,
     * hence you can also call Validate_US::phoneNumber()
     *
     * Can allow only seven digit numbers.
     * Also allows the formats, (xxx) xxx-xxxx, xxx xxx-xxxx,
     * And now x (xxx) xxx-xxxx
     * or various combination without spaces or dashes.
     * THIS SHOULD EVENTUALLY take a FORMAT in the options, instead
     *
     * @param string    $number             phone to validate
     * @param bool      $requireAreaCode    require the area code?
     * @access public
     * @static
     */
    function phoneNumber($number, $requireAreaCode = true)
    {
        if ($number == '') {
            return true;
        }

        if (!$requireAreaCode && preg_match('/^[2-9]\d{2}[- ]?\d{4}$/', $number)) {
            // just seven digits, maybe a space or dash
            return true;
        } else {
            // ten digits, maybe  spaces and/or dashes and/or parentheses maybe a 1 or a 0..
            if (preg_match('/^[0-1]?[- ]?[\(]?[2-9]\d{2}[\)]?[- ]?[2-9]\d{2}[- ]?\d{4}$/', $number)) {
            	return true;
            }
        }
        return false;
    }

}

?>
