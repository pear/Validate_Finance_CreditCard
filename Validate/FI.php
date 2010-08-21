<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * Specific validation methods for data used in Finland
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
 * @package   Validate_FI
 * @author    Jani Mikkonen <jani@mikkonen.info>
 * @copyright 2006-2010 Jani Mikkonen
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD
 * @version   SVN: $Id$
 * @link      http://pear.php.net/package/Validate_FI
 */

// {{{ class Validate_FI
/**
 * Validation class for Finland
 *
 * This class provides methods to validate:
 * - Postal Code
 * - Telephone Number
 * - Car License Plate Number
 * - Motorbike License Plate Number
 * - Personal Identity Number (HETU)
 * - Unique Identification Number (SATU)
 * - Business ID Number (Y-tunnus)
 * - Party Identification Number (OVT-tunnus)
 * - Value Added Tax Number (ALV-numero)
 * - Bank Account Number (tilinumero)
 * - Bank Reference Number (viitenumero)
 * - Credit Card Number
 * 
 * @category  Validate
 * @package   Validate_FI
 * @author    Jani Mikkonen <jani@mikkonen.info>
 * @copyright 2006-2010 Jani Mikkonen
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Validate_FI
 */
class Validate_FI
{

    // {{{ bool Validate_FI::postalCode( string $number [, bool $strong = false] )
    /**
     * Validate Finnish postal code.
     * 
     * Five digit postal code, maybe with a leading 'FI-'.
     * 
     * Format: XXXXX or FI-XXXXX
     * 
     * <code>
     * <?php
     * // Include the package
     * require_once 'Validate/FI.php';
     * 
     * $postalCode = '00100';
     * if ( Validate_FI::postalCode($postalCode) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     * 
     * ?>
     * </code>
     * 
     * @param string $number the postal code to be validated
     * @param bool   $strong optional; strong checks 
     *                       (e.g. against a list of postal codes)
     *
     * @static
     * @access      public
     * @return      bool    true if postal code is valid, false otherwise
     */
    function postalCode($number, $strong=false)
    {
        return (bool) preg_match("/^(FI-)?[0-9]{5}$/", $number);
    }
    // }}}

    // {{{ bool Validate_FI::phoneNumber( string $number )
    /**
     * Validate Finnish telephone number.
     * 
     * Simple check: number must be numeric when (, ), -, +, ., ' ' 
     * chars are removed and 3-20 digit number.
     * 
     * <code>
     * <?php
     * // Include the package
     * require_once 'Validate/FI.php';
     * 
     * $phoneNumber = '+358 40 1234567';
     * if ( Validate_FI::phoneNumber($phoneNumber) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     * 
     * ?>
     * </code>
     * 
     * @param string $number the telephone number to be validated
     *
     * @static
     * @access      public
     * @return      bool    true if telephone number is valid, false otherwise
     */
    function phoneNumber($number)
    {
        $number = str_replace(Array('(', ')', '-', '+', '.', ' '), '', $number);
        return (bool) preg_match("/^[0-9]{3,20}$/", $number);
    }
    // }}}

    // {{{ bool Validate_FI::carLicensePlate( string $number )
    /**
     * Validate Finnish car license plate number.
     * 
     * Format: AAA-XXX, CD-XXXX or C-XXXXX. First or only number cannot be zero.
     * 
     * AAA-XXX: AAA is 2-3 letters UPPERCASE A-Z + ÅÄÖ and XXX is 1-3 numbers.
     * CD-XXXX: CD- and XXXX is 1-4 numbers (diplomat licence plate)
     * C-XXXXX: C- and XXXXX is 1-5 numbers (other tax-free diplomat licence plate)
     * 
     * <code>
     * <?php
     * // Include the package
     * require_once 'Validate/FI.php';
     * 
     * $carLicensePlate = 'ABC-123';
     * if ( Validate_FI::carLicensePlate($carLicensePlate) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     * 
     * ?>
     * </code>
     * 
     * @param string $number the license plate number to be validated
     *
     * @static
     * @access      public
     * @return      bool    true if license plate number is valid, false otherwise
     * @link        http://www.ake.fi/AKE/Rekisterointi/Suomen_rekisterikilvet/
     */
    function carLicensePlate($number)
    {
        // diplomat licence plate
        if (preg_match("/^CD-[1-9]{1}[0-9]{0,3}$/", $number)) {
            return true;
        }
        // other tax-free diplomat licence plate
        if (preg_match("/^C-[1-9]{1}[0-9]{0,4}$/", $number)) {
            return true;
        }
        // regular licence plate
        if (preg_match("/^[A-ZÅÄÖ]{2,3}-[1-9]{1}[0-9]{0,2}$/", $number)) {
            return true;
        }
        return false;
    }
    // }}}

    // {{{ bool Validate_FI::bikeLicensePlate( string $number )
    /**
     * Validate Finnish motorbike license plate number.
     * 
     * Format: AAAXXX. First or only number cannot be zero.
     * 
     * Where AAA is 2-3 letters UPPERCASE A-Z + ÅÄÖ and XXX is 1-3 numbers.
     * Letters and numbers are actually in separate lines.
     * 
     * <code>
     * <?php
     * // Include the package
     * require_once 'Validate/FI.php';
     * 
     * $bikeLicensePlate = 'ABC123';
     * if ( Validate_FI::bikeLicensePlate($bikeLicensePlate) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     * 
     * ?>
     * </code>
     * 
     * @param string $number the license plate number to be validated
     *
     * @static
     * @access      public
     * @return      bool    true if license plate number is valid, false otherwise
     * @link        http://www.ake.fi/AKE/Rekisterointi/Suomen_rekisterikilvet/
     */
    function bikeLicensePlate($number)
    {
        $reg = "/^[A-ZÅÄÖ]{2,3}(\n)?[1-9]{1}[0-9]{0,2}$/";
        return (bool) preg_match($reg, $number);
    }
    // }}}

    // {{{ mixed Validate_FI::pin( string $number [, bool $info = false] )
    /**
     * Validate Personal Identity Number (HETU).
     *
     * The Finnish PIN number (HETU) aka Social Security Number (SSN)
     * is a 11 digit number with birthdate as ddmmyycxxxy where c is century, 
     * xxx is a three digit individual number and the last digit is a 
     * control number (y).
     * 
     * If xxx is odd it's a male PIN number and if even a female.
     * 
     * Return gender (Male or Female) and date of birth (YYYY-MM-DD) in array if
     * PIN is valid, is available by switching $info (2nd parameter) to true.
     * 
     * Example: 010101-123N would be a male and born in 1st of January 1901.
     * 
     * <code>
     * <?php
     * // Include the package
     * require_once 'Validate/FI.php';
     * 
     * $pin = '010101-123N';
     * if ( Validate_FI::pin($pin) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     * 
     * if ( $userinfo = Validate_FI::pin($pin, true) ) {
     *     print_r($userinfo);
     * } else {
     *     print 'Not valid!';
     * }
     * 
     * ?>
     * </code>
     * 
     * @param string $number PIN number to be validated
     * @param bool   $info   optional; Return gender and date of birth on success
     *
     * @static
     * @access      public
     * @return      mixed   Returns true or false if $info = false or
     *                      gender (Male or Female) and date of birth (YYYY-MM-DD)
     *                      in array if PIN is valid, false otherwise
     * @link        http://tarkistusmerkit.teppovuori.fi/tarkmerk.htm#hetu1
     */
    function pin($number, $info = false)
    {
        $regs           = '';
        $pin            = strtoupper($number);
        static $control = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", 
                "A", "B", "C", "D", "E", "F", "H", "J", "K", "L", 
                "M", "N", "P", "R", "S", "T", "U", "V", "W", "X", "Y");
        static $century = array('+' => "18", '-' => "19", 'A' => "20");
        $reg = "/^([0-9]{2})([0-9]{2})([0-9]{2})([+-A]{1})([0-9]{3})([0-9A-Z]{1})$/";
        if (preg_match($reg, $pin, $regs)) {
            // Validate date of birth. Must be a Gregorian date.
            if (checkdate($regs[2], $regs[1], $century[$regs[4]].$regs[3])) {
                $test = $regs[1].$regs[2].$regs[3].$regs[5];
                if ($control[intval($test) % 31] == $regs[6]) {
                    if ($info) {
                        $gen = ($regs[5] % 2 == 1) ? 'Male' : 'Female';
                        $dob = $century[$regs[4]].$regs[3].'-'.$regs[2].'-'.$regs[1];
                        return array($gen, $dob);
                    } else {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    // }}}

    // {{{ bool Validate_FI::finuid( string $number )
    /**
     * Validate Finnish Unique Identification Number (SATU).
     * 
     * FINUID (SATU) is a 9 digit number. The last digit is a control number.
     * 
     * Example: 10011187H
     * 
     * <code>
     * <?php
     * // Include the package
     * require_once 'Validate/FI.php';
     * 
     * $finuid = '10011187H';
     * if ( Validate_FI::finuid($finuid) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     * 
     * ?>
     * </code>
     * 
     * @param string $number FINUID number to be validated
     *
     * @static
     * @access      public
     * @return      bool    true if FINUID is valid, false otherwise
     * @link        http://tarkistusmerkit.teppovuori.fi/tarkmerk.htm#satu
     */
    function finuid($number)
    {
        $regs           = '';
        $number         = strtoupper($number);
        static $control = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", 
                "A", "B", "C", "D", "E", "F", "H", "J", "K", "L", 
                "M", "N", "P", "R", "S", "T", "U", "V", "W", "X", "Y");
        if (preg_match("/^([0-9]{8})([0-9A-Z]{1})$/", $number, $regs)) {
            if ($control[intval($regs[1])%31] == $regs[2]) {
                return true;
            }
        }
        return false;
    }
    // }}}

    // {{{ bool Validate_FI::businessId( string $number )
    /**
     * Validate Finnish Business ID (Y-tunnus).
     * 
     * The Finnish Business ID number (Y-tunnus) is a 9 digit number.
     * The last digit is a control number (y).
     * 
     * Format: xxxxxxx-y
     * 
     * <code>
     * <?php
     * // Include the package
     * require_once 'Validate/FI.php';
     * 
     * $businessId = '1572860-0';
     * if ( Validate_FI::businessId($businessId) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     * 
     * ?>
     * </code>
     * 
     * @param string $number Business ID number to be validated
     *
     * @static
     * @access      public
     * @return      bool    true if Business ID is valid, false otherwise
     * @link        http://tarkistusmerkit.teppovuori.fi/tarkmerk.htm#y-tunnus2
     */
    function businessId($number)
    {
        if (preg_match("/^[0-9]{6,7}-[0-9]{1}$/", $number)) {
            list($num, $control) = preg_split('[-]', $number);
            // Add leading zeros if number is < 7
            $num         = str_pad($num, 7, 0, STR_PAD_LEFT);
            $controlSum  = 0;
            $controlSum += (int)substr($num, 0, 1)*7;
            $controlSum += (int)substr($num, 1, 1)*9;
            $controlSum += (int)substr($num, 2, 1)*10;
            $controlSum += (int)substr($num, 3, 1)*5;
            $controlSum += (int)substr($num, 4, 1)*8;
            $controlSum += (int)substr($num, 5, 1)*4;
            $controlSum += (int)substr($num, 6, 1)*2;
            $controlSum  = $controlSum%11;
            if ($controlSum == 0) {
                return ($controlSum == $control) ? true : false;
            } elseif ($controlSum >= 2 && $controlSum <= 10 ) {
                return ((11 - $controlSum) == $control) ? true : false;
            }
        }
        return false;
    }
    // }}}

    // {{{ bool Validate_FI::partyId( int $number )
    /**
     * Validate Finnish Party Identification number (OVT-tunnus).
     * 
     * The Finnish Party Identification number (OVT-tunnus) is a 12-17
     * digit number and it is generated from Business ID.
     * 
     * Example: 0037AAAAAAAABBBBB, 0037 indicates Finland, AAAAAAAA is the 
     * Business ID and BBBBB is optional organization number.
     * 
     * <code>
     * <?php
     * // Include the package
     * require_once 'Validate/FI.php';
     * 
     * $partyId = '003715728600';
     * if ( Validate_FI::partyId($partyId) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     * 
     * ?>
     * </code>
     * 
     * @param int $number Party Identification number to be validated
     *
     * @static
     * @access      public
     * @return      bool    true if number is valid, false otherwise
     * @see         Validate_FI::businessId()
     * @link        http://tarkistusmerkit.teppovuori.fi/tarkmerk.htm#alv-numero
     */
    function partyId($number)
    {
        if (preg_match("/^[0-9]{12,17}$/", $number)) {
            $countryCode = substr($number, 0, 4);
            $controlNum  = substr($number, 11, 1);
            $businessNum = substr($number, 4, 7);
            $businessId  = $businessNum.'-'.$controlNum;
            if ($countryCode == '0037' && Validate_FI::businessId($businessId)) {
                return true;
            }
        }
        return false;
    }
    // }}}

    // {{{ bool Validate_FI::vatNumber( string $number )
    /**
     * Validate Finnish Value Added Tax number (ALV-numero).
     * 
     * The Finnish VAT number (ALV-numero) is maximum 
     * of 14 digits and is generated from Business ID.
     * 
     * Format: FIXXXXXXXX
     * 
     * <code>
     * <?php
     * // Include the package
     * require_once 'Validate/FI.php';
     * 
     * $vatNumber = 'FI15728600';
     * if ( Validate_FI::vatNumber($vatNumber) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     * 
     * ?>
     * </code>
     * 
     * @param string $number VAT number to be validated
     *
     * @static
     * @access      public
     * @return      bool    true if VAT number is valid, false otherwise
     * @see         Validate_FI::businessId()
     * @link        http://tarkistusmerkit.teppovuori.fi/tarkmerk.htm#alv-numero
     */
    function vatNumber($number)
    {
        $countryCode = substr($number, 0, 2);
        $controlNum  = substr($number, -1, 1);
        $businessNum = substr($number, 2, -1);
        $businessId  = $businessNum.'-'.$controlNum;
        if ($countryCode == 'FI' && Validate_FI::businessId($businessId)) {
            return true;
        }
        return false;
    }
    // }}}

    // {{{ bool Validate_FI::bankAccount( string $number )
    /**
     * Validate Finnish bank account number.
     * 
     * This method checks the bank account number according to 
     * Finnish Bank Association.
     * 
     * Format: XXXXXX-XXXXXXXX, 6 digits, - and 2-8 digits.
     * 
     * <code>
     * <?php
     * // Include the package
     * require_once 'Validate/FI.php';
     * 
     * $bankAccount = '159030-776';
     * if ( Validate_FI::bankAccount($bankAccount) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     * 
     * ?>
     * </code>
     * 
     * @param string $number Finnish bank account number to be validated
     *
     * @static
     * @access public
     * @return bool    true if bank account is valid, false otherwise
     * @link   http://tarkistusmerkit.teppovuori.fi/tarkmerk.htm#pankkitili
     * @link   http://www.pankkiyhdistys.fi/sisalto/upload/pdf/tilinrorakenne.pdf
     */
    function bankAccount($number)
    {
        if (preg_match("/^[0-9]{6}-[0-9]{2,8}$/", $number)) {
            // Bank groups are identified by the first digit
            $bankType = substr($number, 0, 1);
            // Group 1: First digit is 1, 2, 3, 6 or 8
            $bankGroup1 = array('1', '2', '3', '6', '8');
            // Group 2: First digit is 4 or 5
            $bankGroup2 = array('4', '5');
            // split account number
            $regs = '';
            preg_match("/([0-9]{6})-([0-9]{2,8})/", $number, $regs);
            if (in_array($bankType, $bankGroup1)) {
                // Group 1: 999999-99999 -> 999999-00099999
                $number = $regs[1] . str_pad($regs[2], 8, 0, STR_PAD_LEFT);
                // Group 2: 999999-99999 -> 999999-90009999
            } else if (in_array($bankType, $bankGroup2)) {
                $number = $regs[1] . substr($regs[2], 0, 1) . 
                    str_pad(substr($regs[2], 1, 7), 7, 0, STR_PAD_LEFT);
            }
            // Now when we have a 14 digit bank account number, we can validate it.
            return Validate_FI::_mod10($number);
        }
        return false;
    }
    // }}}

    // {{{ bool Validate_FI::refNum( string $number )
    /**
     * Validate Finnish bank reference number.
     * 
     * This method checks the bank reference number according to 
     * Finnish Bank Association.
     * 
     * <code>
     * <?php
     * // Include the package
     * require_once 'Validate/FI.php';
     * 
     * $refNum = '61 74354';
     * if ( Validate_FI::refNum($refNum) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     * 
     * ?>
     * </code>
     * 
     * @param string $number Finnish bank reference number to be validated
     *                       (spaces and dashes tolerated)
     *
     * @static
     * @access      public
     * @return      bool    true if reference number is valid, false otherwise
     * @link        http://tarkistusmerkit.teppovuori.fi/tarkmerk.htm#viitenumero
     */
    function refNum($number)
    {
        // Remove non-numeric characters from $refnum. Only 4-20 digit number.
        $refnum = preg_replace('/[^0-9]+/', '', $number);
        // The last digit is a control number.
        $controlNum = substr($refnum, -1, 1);
        // Subtract control number from the $refnum.
        $refNumCheck = substr($refnum, 0, strlen($refnum)-1);
        if (preg_match("/^[0-9]{3,19}$/", $refNumCheck)) {
            // remove leading zeros
            $refNumCheck = ltrim($refNumCheck, 0);
            $mul         = 7;
            $refSum      = 0;
            for ($refLength = strlen($refNumCheck); $refLength > 0; $refLength--) {
                $refSum += substr($refNumCheck, $refLength - 1, 1) * $mul;
                switch ($mul) {
                case 7:
                    $mul = 3;
                    break;
                case 3:
                    $mul = 1;
                    break;
                case 1:
                    $mul = 7;
                    break;
                }
            }
            $refSum = substr(10 - ($refSum % 10), -1);
            if ($refSum == $controlNum) {
                return true;
            }
        }
        return false;
    }
    // }}}

    // {{{ bool Validate_FI::creditCard( string $number )
    /**
     * Validate credit card number.
     * 
     * This method checks the credit card number according to Luhn algorithm.
     * This method doesn't guarantee that the card is legitimate.
     * 
     * <code>
     * <?php
     * // Include the package
     * require_once 'Validate/FI.php';
     * 
     * $creditCard = '5427 0073 1297 6425';
     * if ( Validate_FI::creditCard($creditCard) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     * 
     * ?>
     * </code>
     * 
     * @param string $number credit card number to be validated
     *                        (spaces and dashes tolerated)
     *
     * @static
     * @access      public
     * @return      bool    true if credit card number is valid, false otherwise
     */
    function creditCard($number)
    {
        // Remove non-numeric characters from $number 
        $number = preg_replace('/[^0-9]+/', '', $number);
        // Validate number
        return Validate_FI::_mod10($number);
    }
    // }}}

    // {{{ bool Validate_FI::_mod10( string $number )
    /**
     * Validate number according to Luhn algorithm (mod 10).
     * 
     * This method checks given number according Luhn algorithm.
     * 
     * @param string $number to be validated
     *
     * @static
     * @access      private
     * @return      bool    true if number is valid, false otherwise
     * @link        http://en.wikipedia.org/wiki/Luhn_algorithm
     */
    function _mod10($number)
    {
        // Double every second digit started at the right
        $doubledNumber = '';
        $odd           = false;
        for ($i = strlen($number)-1; $i >=0; $i--) {
            $doubledNumber .= ($odd) ? $number[$i]*2 : $number[$i];
            $odd            = !$odd;
        }
        // Add up each 'single' digit
        $sum = 0;
        for ($i = 0; $i < strlen($doubledNumber); $i++) {
            $sum += (int)$doubledNumber[$i];
        }
        // A valid number doesn't have a remainder after mod10 or equal to 0
        return (($sum % 10 == 0) && ($sum != 0)) ? true : false;
    }
    // }}}
}
// }}}

?>
