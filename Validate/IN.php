<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Specific validation methods for data used in India
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
 * @package   Validate_IN
 * @author    Anant Narayanan <anant@php.net>
 * @author    Byron Adams <byron.adams54@gmail.com>
 * @copyright 1997-2005 Anant Narayanan
 * @copyright 2006 Byron Adams
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_IN
 */
    
/**
 * Specific validation for data pertaining to the Republic of India.
 *
 * This class will validate Indian:
 *  - Postal Codes (Zip Codes)
 *  - State/U.T. Codes
 *  - Telephone Numbers
 *  - PAN/TAN Numbers
 *  - Vehicle license plate Numbers
 *
 * @category  Validate
 * @package   Validate_IN
 * @author    Anant Narayanan <anant@php.net>
 * @author    Byron Adams <byron.adams54@gmail.com> 
 * @copyright 1997-2005 Anant Narayanan
 * @copyright 2006 Byron Adams
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @link      http://pear.php.net/package/Validate_IN
 */

class Validate_IN
{
    /**
     * Validates an Indian Permanent Account Number (PAN) or
     * Tax deduction and collection Account Number (TAN).
     *
     * @param string $number the PAN or TAN to be validated.
     *
     * @access  public
     * @return  bool true on success false otherwise
     */
    function pan($number)
    {
        return preg_match('/^[A-Z]{3}[A-Z0-9]{7}$/', $number);
    }

    /**
     * Validates a state / union territory code and returns the full name of the
     * state / union territory code passed.
     *
     * @param string $stateCode 2-letter region code
     *
     * @access  public
     * @see     Validate_IN::region()
     * 
     * @return  bool true on success false otherwise
     */
    function stateCode($stateCode)
    {
        return Validate_IN::region($stateCode);   
    }

    /**
     * Validates an Indian Vehicle's license plate number.
     *
     * @param string $number the license plate number to validate.
     *
     * @access  public
     * 
     * @return  bool      true on success false otherwise
     */
    function licensePlate($number)
    {
        $regex = "/^[A-Z]{2}(\s|\-)?[0-9]{1,2}(\s|\-)?(S|C|R|V)?"
               . "(\s|\-)?[A-Z]{0,2}(\s|\-)?\d{4}$/";

        if (Validate_IN::stateCode(substr($number, 0, 2))) {
            return preg_match($regex, $number);
        }

        return false; 
    }

    /**
     * Validates an Indian Postal Code (ZIP code)
     *
     * @param string $postalCode The ZIP code to validate
     *
     * @access  public
     * 
     * @return  bool true on success false otherwise
     */
    function postalCode($postalCode)
    {
        $postalCode = preg_replace("/[^\d]/", "", $postalCode);

        return (strlen($postalCode) == 6
                && $postalCode{0} != "0");
    }

    /**
     * Validates an Indian Permanent Account Number (PAN) or
     * Tax deduction and collection Account Number (TAN).
     * 
     * @param string $ssn The PAN or TAN to be validated.
     *
     * @access  public
     * @see     Validate_AU::pan()
     * 
     * @return  bool true on success false otherwise
     * 
     */
    function ssn($ssn)
    {
        return Validate_IN::pan($ssn);
    }

    /**
     * Validates a state / union territory code and returns the full name of the
     * state / union territory code passed.
     *
     * @param string $region 2-letter region code
     *
     * @access  public
     * @return  bool      true on success false otherwise
     */
    function region($region)
    {
        static $states = array(
                "AN", "AP", "AR", "AS", "BR", "CH", "CT", "DN", "DD",
                "DL", "GA", "GJ", "HR", "HP", "JK", "JH", "KA", "KL",
                "LD", "MP", "MH", "MN", "ML", "MZ", "NL", "OR", "PY",
                "PB", "RJ", "SK", "TN", "TR", "UL", "UP", "WB"); 

        return in_array(strtoupper($region), $states);
    }

    /**
     * Returns the full name of a state / union territory given a valid state
     * code. If state code is invalid or NULL, an array of all states is
     * returned.
     * 
     * @param string $code 2-letter region code
     *
     * @return  bool      true on success false otherwise
     * @return  mixed     Full name of state of code is valid, array of
     *                    all states if not.
     * @access  public
     */
    function getStateName($code = null)
    {
        $code = strtoupper($code);

        static $states = array(
                "AN" => "Andaman and Nicobar Islands",
                "AP" => "Andhra Pradesh",
                "AR" => "Arunachal Pradesh",
                "AS" => "Assam",
                "BR" => "Bihar",
                "CH" => "Chandigarh",
                "CT" => "Chattisgarh",
                "DN" => "Dadra and Nagar Haveli",
                "DD" => "Daman and Diu",
                "DL" => "Delhi",
                "GA" => "Goa",
                "GJ" => "Gujarat",
                "HR" => "Harayana",
                "HP" => "Himachal Pradesh",
                "JK" => "Jammu and Kashmir",
                "JH" => "Jharkhand",
                "KA" => "Karnataka",
                "KL" => "Kerala",
                "LD" => "Lakshwadeep",
                "MP" => "Madhya Pradesh",
                "MH" => "Maharashtra",
                "MN" => "Manipur",
                "ML" => "Meghalaya",
                "MZ" => "Mizoram",
                "NL" => "Nagaland",
                "OR" => "Orissa",
                "PY" => "Pondicherry",
                "PB" => "Punjab",
                "RJ" => "Rajasthan",
                "SK" => "Sikkim",
                "TN" => "Tamil Nadu",
                "TR" => "Tripura",
                "UL" => "Uttaranchal",
                "UP" => "Uttar Pradesh",
                "WB" => "West Bengal");

        if (array_key_exists($code, $states)) {  
            return $states[$code];    
        } else {
            return $states;
        }
    }

    /**
     * Validate an Indian Phone number.
     *
     * Allows the following formats:
     *
     *  (xxx) xxxxxxx
     *  xxx xxxxxxx
     *  +91 xxx xxxxxxx
     *  091xxxxxxxxxx
     *
     * where whitespaces, dashes and brackets may interchanged freely and 0/+ may
     * be added / skipped wherever possible.
     *
     * @param string $number Phone number to validate (mobile or landline)
     *
     * @access  public
     * @static
     * @return  bool True if number is valid, False otherwise
     */
    function phoneNumber($number)
    {
        // strip country code
        if (substr($number, 0, 3)=='091' or substr($number, 0, 3)=='+91') {
            $number = substr($number, 3, strlen($number)-3);
        }
        // no numbers in India begin with 91, so safely strip country code
        if (substr($number, 0, 2)=='91') {
            $number = substr($number, 2, strlen($number)-2);
        }

        // it's a mobile number
        if (preg_match('/^9(2|3|4|8|9)(\s)?(\-)?(\s)?[1-9]{1}[0-9]{7}$/', $number)) {
            return true;
        }

        // it's a landline, with or without area code
        $reg = "/^\(?(0[1-9]{2,5}|\d{2,5})?(\s)?(\s|\)|\-)?(\s)?(2|3|5)\d{6,7}$/";
        if (preg_match($reg, $number)) {          
            return true;
        }

        return false;
    }



}
?>
