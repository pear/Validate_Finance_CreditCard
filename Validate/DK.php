<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005 Jesper Veggerby Hansen                       |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
// | Author:  Jesper Veggerby <pear.nosey@veggerby.dk>                    |
// +----------------------------------------------------------------------+
//
/**
 * Specific validation methods for data used in DK
 *
 * @category   Validate
 * @package    Validate_DK
 * @author     Jesper Veggerby <pear.nosey@veggerby.dk>
 * @copyright  Copyright (C) 2003, 2004, 2005 Jesper Veggerby Hansen
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_DK
 */

/**
 * Data validation class for Denmark
 *
 * This class provides methods to validate:
 *
 * - Postal code
 * - Social Security Number (CPR Nummer)
 * - German bank code
 * - Car registration number
 *
 * @category   Validate
 * @package    Validate_DK
 * @author     Jesper Veggerby <pear.nosey@veggerby.dk>
 * @copyright  Copyright (C) 2003, 2004, 2005 Jesper Veggerby Hansen
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @link       http://pear.php. net/package/Validate_DK
 */
class Validate_DK
{

    /**
     * validates a postcode
	 *
     * Four digit postal code, maybe with a leading 'DK-'
     *
     * @access    public
     * @param     string  the postcode to be validated
     * @param     bool    optional; strong checks (e.g. against a list of postcodes)
     * @return    bool
     */
    function postalCode($postcode, $strong=false)
    {
        $preg = "/^(DK-)?[0-9]{4}$/";
        $match = preg_match($preg, $postcode)? true : false;
        return $match;
    }

    /**
     * validates a CPR Number (ssn equiv)
     *
     * The danish CPR number is a 8 digit number with the birthdate as
     * ddmmyy-xxxy where xxxy is a four digit control number.
     *
     * The 10 digits are summarized with coefficients 4, 3, 2, 7, 6, 5, 4, 3, 2
     * and 1. If the sum is divisible by 11 the control is correct.
     *
     * The last digit of the control number (y) is also dependend on gender, if
     * y is odd it's a male cpr number and if even a female.
     *
     * @access    public
     * @param     string $cpr CPR number
     * @param     string $gender The gender to validate for 'M' for male, 'F'
     * for female, false or omitted to not perform the check
     * @return    bool
     */
    function ssn($cpr, $gender = false){
        static $control = array(4, 3, 2, 7, 6, 5, 4, 3, 2, 1);

        // remove spaces and uppercase it
        $preg = "/^[0-9]{6}\-?[0-9]{4}$/";
        if (preg_match($preg, $cpr)) {
            $cpr = str_replace('-', '', $cpr);
            $controlCipher = 0;
            for ($i = 0; $i < count($control); $i++) {
                $controlCipher += $control[$i] * substr($cpr, $i, 1);
            }
            $y = substr($cpr, -1);
            switch ($gender) {
            case 'M':
                $genderOK = (($y % 2) == 1);
                break;
            case 'F':
                $genderOK = (($y % 2) == 0);
                break;
            default:
                $genderOK = true;
                break;
            }
            return ((($controlCipher % 11) === 0) && ($genderOK));
        } else {
            return false;
        }
    }

    /**
     * Validate danish telephone number
     *
     * Simple check: 8 digits when removing (, ), -, +, ., ' '
     *
     * @access    public
     * @param     string $tel the tel number
     * @return    bool
     * @see
     */
    function phoneNumber($tel){
        // just checks to see if it is numeric and starts with a 0
        // remove any wierd characters like (,),-,. etc
        $tel = str_replace(Array('(', ')', '-', '+', '.', ' '), '', $tel);
        $preg = "/^[0-9]{8}$/";
        $match = (preg_match($preg, $tel)) ? true : false;
        return $match;
    }

    /**
     * Validates a car registration number
     *
     * Format: AA XX YYY
     *
     * Where AA are 2 letter UPPERCASE A-Z
     *
     * @access    public
     * @author    Michael Dransfield <mikeNO@SPAMblueroot.net>
     * @param     string $reg the registration number
     * @return    bool
     */
    function carReg($reg){
        $prepreg = "/^[A-Z]{2} [0-9]{2} [0-9]{3}$/";
        if (preg_match($prepreg, $reg)) {
            return true;
        } else {
            return false;
        }
    }
}

?>
