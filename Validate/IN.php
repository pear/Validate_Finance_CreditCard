<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005 Anant Narayanan                              |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
// | Author: Anant Narayanan <anant@php.net>                              |
// |         Byron Adams <byron.adams54@gmail.com>                        |
// +----------------------------------------------------------------------+
//
/**
 * Specific validation methods for data used in India
 *
 * @category   Validate
 * @package    Validate_IN
 * @author     Anant Narayanan <anant@php.net>
 * @author     Byron Adams <byron.adams54@gmail.com>
 * @copyright  1997-2005 Anant Narayanan
 * @copyright  (c) 2006 Byron Adams
 * @version    CVS: $Id$
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
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
 * @category   Validate
 * @package    Validate_IN
 * @author     Anant Narayanan <anant@php.net>
 * @author     Byron Adams <byron.adams54@gmail.com> 
 * @copyright  1997-2005 Anant Narayanan
 * @copyright  (c) 2006 Byron Adams
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 */

class Validate_IN
{
    /**
     * Validates an Indian Permanent Account Number (PAN) or
     * Tax deduction and collection Account Number (TAN).
     *
     * @access  public
     * @see     Validate_IN::ssn()
     * @author  Byron Adams
     * @author  Anant Narayanan
     * 
     * @param   string    $number the PAN or TAN to be validated.
     * @return  bool      true on success false otherwise
     */
    function pan($number)
    {
        return Validate_IN::ssn($number);
    }

    /**
     * Validates a state / union territory code and returns the full name of the
     * state / union territory code passed.
     *
     * @access  public
     * @see     Validate_IN::region()
     * @author  Byron Adams
     * @author  Anant Narayanan
     * 
     * @param   string    $region 2-letter region code
     * @return  bool      true on success false otherwise
     */
    function stateCode($stateCode)
    {
        return Validate_IN::region($stateCode);   
    }
    
    /**
     * Validates an Indian Vehicle's license plate number.
     *
     * @access  public
     * @see     Validate_IN::carReg()
     * @author  Byron Adams
     * @author  Anant Narayanan
     * 
     * @param   string    $number; the license plate number to validate.
     * @return  bool      true on success false otherwise
     */
    function licensePlate($number)
    {
        return Validate_IN::carReg($number);    
    }
    
    /**
     * Validates an Indian Postal Code (ZIP code)
     *
     * @access  public
     * @author  Byron Adams
     * @author  Anant Narayanan
     * 
     * @param   string    $postalCode The ZIP code to validate
     * @return  bool      true on success false otherwise
     */
    function postalCode($postalCode)
    {
        return (ctype_digit($postalCode) 
                && strlen($postalCode) == 6);
    }

    /**
     * Validates an Indian Permanent Account Number (PAN) or
     * Tax deduction and collection Account Number (TAN).
     * 
     * @access  public
     * @author  Byron Adams
     * 
     * @param   string    $ssn The PAN or TAN to be validated.
     * @return  bool      true on success false otherwise
     * 
     */
    function ssn($ssn)
    {
        $ssn = trim($rssn);
        return (ctype_alnum($ssn)
            && !ctype_alpha($ssn)
            && !ctype_digit($ssn)
            && strlen($ssn) == 10);
    }
    
    /**
     * Validates an Indian Vehicle's license plate number.
     *
     * @access  public
     * @author  Byron Adams
     * 
     * @param   string    $reg The license plate number to validate.
     * @return  bool      true on success false otherwise
     */
    function carReg($reg)
    {
        if (Validate_IN::region(substr($reg, 0, 2))) {
            
            return (ctype_alnum($reg)
                && !ctype_alpha($reg)
                && !ctype_digit($reg)
                && strlen($reg) == 6);
        } 

        return false;
    }   
    
    /**
     * Validates a state / union territory code and returns the full name of the
     * state / union territory code passed.
     *
     * @access  public
     * @author  Byron Adams
     * @author  Anant Narayanan
     * 
     * @param   string    $region 2-letter region code
     * @return  bool      true on success false otherwise
     */
    function region($region)
    {
        static $states = array(
            "AN", "AP", "AR", "AS", "BR", "CH", "CT", "DN", "DD",
            "DL", "GA", "GJ", "HR", "HP", "JK", "JH", "KA", "KL",
            "LD", "MP", "MH", "MN", "ML", "MZ", "NL", "OR", "PY",
            "PB", "RJ", "SK", "TN", "TR", "UL", "UP", "WB"); 
        
        return in_array(strtoupper($region),$regions);
    }

    /**
     * Returns the full name of a state / union territory given a valid state
     * code. If state code is invalid or NULL, an array of all states is
     * returned.
     *
     * @access  public
     * @author  Byron Adams
     * @author  Anant Narayanan
     * 
     * @param   string    $code 2-letter region code
     * @return  bool      true on success false otherwise
     * @return  mixed     Full name of state of code is valid, array of
     *                    all states if not.
     */
    function getStateName($code = NULL)
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
     * @param   string  $number         Phone number to validate (mobile or
     *                                  landline)
     *
     * @return  bool    True if number is valid, False otherwise
     *
     * @access  public
     * @static
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
        if (preg_match('/^\(?(0[1-9]{2,5}|\d{2,5})?(\s)?(\s|\)|\-)?(\s)?(2|3|5)\d{6,7}$/',
            $number)) {          
            return true;
        }
       
        return false;
    }

    
    
}
?>
