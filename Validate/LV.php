<?php

// +----------------------------------------------------------------------+
// | Copyright (c) 2006 Vide Infra Grupa SIA                                  |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
//
/**
 * Specific validation methods for data used in Latvia
 *
 * @category   Validate
 * @package    Validate_LV
 * @author     Aigars Gedroics
 * @author     Roman Roan <opensourceNO@SPAMvideinfra.com>
 * @copyright  2006 Vide Infra Grupa SIA
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    1.1 $id: $
 * @link       http://pear.php.net/package/Validate_LV
 */

/**
 * Data validation class for Latvia
 *
 * This class provides methods to validate:
 *  - VAT number
 *  - Registration number
 *  - Swift code
 *  - Telephone number
 *  - Person ID
 *  - IBAN Bank account number for Latvian Banks
 *  - Postal code
 *  - Passport
 *  - Person name
 *
 */
class Validate_LV {
    /**
     * Validates VAT number of an organization
     * 
     * @access  public
     * @param   string  The VAT number to be validated
     * @return    bool
     */
    function vatNumber($vat) {
        return preg_match('/^LV\d{11}$/i', $vat);
    }

    /**
     * Validates registration number of an organization
     * 
     * @access  public
     * @param   string  The registration number to be validated
     * @return    bool
     */
    function registrationNumber($regNr) {
        return preg_match('/^\d{11}$/', $regNr);
    }
    /**
     * Validates Latvian bank SWIFT code
     * e.g. AIZKLV22
     * 
     * @access  public
     * @param   string  The swift code to be validated
     * @return    bool
     */
    function swift($swift) {
        return preg_match('/^[a-z0-9]{4}LV[a-z0-9]{2}$/i', $swift);
    }

    /**
     * Validates phone number
     * Latvian phone code is +371
     * Phone number without country code consists of 7 or 8 digits
     * 
     * @access  public
     * @param   string  The phone number to be validated
     * @return    bool
     */
    function phoneNumber($number) {
        $number = str_replace(array (
            '(',
            ')',
            '-',
            '.',
            ' '
        ), '', $number);
        return preg_match('/^(\+371)?[\d]{7,8}$/', $number);
    }

    /**
     * Validate latvian person Id (latvian social security number)
     * e.g. 111111-11111 is valid person id for person born in November 11th 1911.
     * The last digit serves as checkdigit
     * 
     * @access  public
     * @param   string  The person id to be validated
     * @return    bool
     */
    function personId($personId) {

        // Checking pattern
        if (!preg_match('/^\d{6}-[1-9]\d{4}$/', $personId))
            return false;
        $personId = str_replace('-', '', $personId);

        // Century '20' isn't picked ocasionaly: 
        // It's because we can't know century and 2000 year is leap, but 1900 isn't
        // (in 2000 there is the February 29th, in 1900 isn't)
        // In fact everything between 4 and 324 would match
        $validDate = checkdate(substr($personId, 2, 2), substr($personId, 0, 2), '20' . substr($personId, 4, 2));
		if (!$validDate)
		{
			return false;
		}
        

        // Checking last digit
        $mult = Array (
            1,
            6,
            3,
            7,
            9,
            10,
            5,
            8,
            4,
            2,
            1
        );
        $sum = 0;
        for ($i = 0; $i < 11; $i++) {
            $sum += $mult[$i] * $personId {
                $i };
        }
        return $sum % 11 == 1;
    }

    /**
     * Validate latvian account number
     * 
     * @access  public
     * @param   string  The account number to be validated
     * @return    bool
     */
    function IBAN($iban, $swift = false) {
        if ($swift) {
            $swift = substr($swift, 0, 4);
            if (substr($iban, 4, 4) != $swift) {
                return false;
            }
        }
        $iban = strtoupper($iban);
        if (!preg_match('/^LV[0-9]{2}[A-Z]{4}[0-9A-Z]{13}$/', $iban))
            return false;
        $iban = substr($iban, 4) . substr($iban, 0, 4);
        $sNew = '';
        for ($i = 0; $i < 21; $i++) {
            $digit = $iban {
                $i };
            $code = ord($digit);
            if ($code > 64 && $code < 91)
                $digit = $code -55;
            $sNew = $sNew . $digit;
            $sNew = (int) $sNew % 97;
            if ($sNew == 0)
                $sNew = '';
        }
        return ($sNew == 1);
    }

    /**
     * Validate Latvian postcode
     * e.g. LV-1004
     *
     * @access  public
     * @param   string  the postcode to be validated
     * @return  bool
     */
    function postalCode($postcode) {
        $postcode = strtoupper(str_replace(' ', '', $postcode));
        return preg_match('/^LV-[1-9]\d{3}$/', $postcode);
    }

    /**
     * Validates a Latvian passport number
     * e.g. LF321654741
     *
     * @access    public
     * @param     string
     * @return    bool
     */
    function passport($passportNumber) {
        return preg_match('/^[a-z]{2}[0-9]{7}$/i', $passportNumber);
    }

    /**
     * Validates a Latvian person name
     * First letter must be uppercase
     * Can contain latin letters, latvian standart letters, symbols ' ' and '-'
     * e.g. Jānis Kārkliņš
     *
     * @access    public
     * @param     string
     * @param     integer
     * @return    bool
     */
    function personName($name, $length = 50) {
        $length--;
        return ereg('^([A-Z]|Ē|Ū|Ī|Ā|Š|Ģ|Ķ|Ļ|Ž|Č|Ņ)[a-zA-ZēūīāšģķļžčņĒŪĪĀŠĢĶĻŽČŅ -]{1,'.$length.'}$',$name);	
    }

}
?>
