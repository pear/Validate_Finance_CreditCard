<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Specific validation methods for data used in the Netherlands
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
 * @package   Validate_NL
 * @author    Dave Mertens <zyprexia@php.net>
 * @author    Pierre-Alain Joye <pajoye@php.net>
 * @copyright 1997-2005 Dave Mertens
 * @license   http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_NL
 */

//Any dutch phonenumber
define('VALIDATE_NL_PHONENUMBER_TYPE_ANY', 0);
//only normal phonenumber (mobile numers are not allowed)
define('VALIDATE_NL_PHONENUMBER_TYPE_NORMAL', 1);
//only mobile numbers are allowed
define('VALIDATE_NL_PHONENUMBER_TYPE_MOBILE', 2);

/**
 * Data validation class for the Netherlands
 *
 * This class provides methods to validate:
 *  - Social insurance number (aka SIN)
 *  - Bank account number
 *  - Telephone number
 *  - Postal code
 *
 * @category  Validate
 * @package   Validate_NL
 * @author    Dave Mertens <zyprexia@php.net>
 * @copyright 1997-2005 Dave Mertens
 * @license   http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Validate_NL
 */
class Validate_NL
{
    /**
     * Validate a NL postcode
     *
     * @param string $postcode NL postcode to validate
     * @param bool   $strong   optional; strong checks (e.g. against a list 
     *                         of postcodes) (not implanted)
     *
     * @return  bool    true if postcode is ok, false otherwise
     */
    function postalCode($postcode, $strong = false)
    {
        // $strong is not used here at the moment; added for API compatibility
        // checks might be added at a later stage

        // '1234 AB', '1234AB', '1234 ab'
        return (bool)ereg('^[0-9]{4}\ {0,1}[A-Za-z]{2}$', $postcode); 
    }

    /**
     * Validate a phonenumber
     *
     * @param string $number Dutch phonenumber (can be in international format 
     *                       (eg +31 or 0031)
     * @param int    $type   Type of phonenumber to check
     *
     * @return  bool    true if (phone) number is correct
     */
    function phoneNumber($number, $type = PHONENUMBER_TYPE_ANY)
    {
        $result = false;

        //we need at least 9 digits
        if (ereg("^[+0-9]{9,}$", $number)) {
            $number = substr($number, strlen($number) - 9);

            //we only use the last 9 digits (so no troubles with 
            //international numbers)
            if (strlen($number) >= 9) {
                switch ($type) {
                case VALIDATE_NL_PHONENUMBER_TYPE_ANY:
                    //we have a 9 digit numeric number.
                    $result = true;     
                    break;
                case VALIDATE_NL_PHONENUMBER_TYPE_NORMAL:
                    //normal phonenumbers don't begin with 6 (00316, +316 
                    //and 06 are reserved for mobile numbers)
                    if ((int)$number[0] != 6)
                        $result = true;     
                    break;
                case VALIDATE_NL_PHONENUMBER_TYPE_MOBILE:
                    //mobilenumbers start with a 6
                    if ((int)$number[0] == 6)
                        $result = true;     
                    break;
                }
            }
        }

        return $result;
    }


    /**
     * Social Security Number check (very simple, just a 9 digit number..)
     * In Dutch SoFi (Sociaal Fiscaal) nummer
     *
     * @param string $ssn Dutch social security number
     *
     * @return bool true if SSN number is correct
     */
    function ssn($ssn)
    {
        return (bool) ereg("^[0-9]{9}$", $ssn);
    }

    /**
     * Bankaccount validation check (based on 11proef)
     *
     * @param string $number Dutch bankaccount number
     *
     * @return bool true is bankaccount number 'seems' correct
     */
    function bankAccountNumber($number)
    {
        $result   = false;        //by default we return false
        $checksum = 0;

        if (is_numeric((string)$number) && strlen((string)$number) <= 10) {
            //make sure we have a 10 digit number
            $number = str_pad($number, 10, '0', STR_PAD_LEFT);  

            //create checksum
            for ($i = 0; $i < 10; $i++) {
                $checksum += ((int)$number[$i] * (10 - $i));
            }

            //Banknumber is 'correct' if we can divide checksum by 11
            if ($checksum > 0 && $checksum % 11 == 0)
                $result = true;
        }

        //return result
        return $result;
    }

}
?>
