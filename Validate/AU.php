<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005 Daniel O'Connor                              |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
// | Author: Daniel O'Connor <daniel.oconnor@gmail.com>                   |
// |         Alex Hayes <ahayes@wcg.net.au>                               |
// |         Byron Adams <byron.adams54@gmail.com>                        |
// +----------------------------------------------------------------------+
//
/**
 * Specific validation methods for data used in Australia
 *
 * @category   Validate
 * @package    Validate_AU
 * @author     Daniel O'Connor <daniel.oconnor@gmail.com>
 * @author     Tho Nguyen <tho.nguyen@itexperts.com.au>
 * @author     Alex Hayes <ahayes@wcg.net.au>
 * @author     Byron Adams <byron.adams54@gmail.com>
 * @copyright  1997-2005 Daniel O'Connor
 * @copyright  (c) 2006 Alex Hayes
 * @copyright  (c) 2006 Byron Adams
 * @version    CVS: $Id$
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 */

define("VALIDATE_AU_PHONE_STRICT",        1);
define("VALIDATE_AU_PHONE_NATIONAL",      2);
define("VALIDATE_AU_PHONE_INDIAL",        4);
define("VALIDATE_AU_PHONE_INTERNATIONAL", 8);

/**
 * Data validation class for Australia
 *
 * Contains code from Validate_AT, Validate_UK and Validate_NZ
 *
 * This class provides methods to validate:
 *  - Postal code
 *  - Phone number
 *  - Australian Business Number
 *  - Australian Company Number
 *  - Tax File Number
 *  - Australian Regional codes
 *
 * @category   Validate
 * @package    Validate_AU
 * @author     Daniel O'Connor <daniel.oconnor@gmail.com>
 * @author     Tho Nguyen <tho.nguyen@itexperts.com.au>
 * @author     Alex Hayes <ahayes@wcg.net.au>
 * @author     Byron Adams <byron.adams54@gmail.com>
 * @copyright  1997-2005 Daniel O'Connor
 * @copyright  (c) 2006 Alex Hayes
 * @copyright  (c) 2006 Byron Adams
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 */

class Validate_AU
{

    /**
     * Validate Austrialian postal codes.
     *
     * @access   public
     * @static   string  $postcodes
     * @param    string  $postcode  postcode to validate
     * @param    bool    $strong optional; strong checks against a list of postcodes
     * @return   bool    true if postcode is ok, false otherwise
     */
    function postalCode($postcode, $strong = false)
    {
        if ($strong) {
            static $postcodes;

            if (!isset($postcodes)) {
                $file = '@DATADIR@/Validate_AU/data/AU_postcodes.txt';
                $postcodes = array_map('trim', file($file));
            }

            return in_array((int)$postcode, $postcodes);
        }
        return preg_match('^[0-9]{4}$', $postcode);
    }
    
   /**
    * Validates Australian Regional Codes
    *
    * @author    Byron Adams <byron.adams54@gmail.com>
    * @access    public
    * @static    array      $regions
    * @param     string     $region, regional code to validate
    * @return    bool       Returns true on success, false otherwise
    * @link      http://www.google.com/apis/adwords/developer/adwords_api_regions.html#Australia
    */
   function region($region)
   {
       static $regions = array("ACT", "NSW", "NT", "QLD", "SA", "TAS", "VIC", "WA");
       return in_array(strtoupper($region),$regions);
   }

    /**
     * Validate a telephone number.
     *
     * Note that this function supports the following notations:
     * 
     *     - Landline: 03 9999 9999
     *     - Mobile: 0400 000 000 (as above, but usually notated differently)
     *     - Indial: 131 812 / 1300 000 000 / 1800 000 000 / 1900 000 000
     *     - International: +61.3 9999 9999
     * 
     * For International numbers, only +61 will be valid, as this is
     * Australia's dial code, and the format MUST be +61.3, where 3 represents
     * the state dial code, in this case, Victoria.
     * 
     * Note: If the VALIDATE_AU_PHONE_STRICT flag is not supplied, then all spaces, 
     * dashes and parenthesis are removed before validation. You will have to 
     * strip these yourself if your data storage does not allow these characters.
     *
     * @static
     * @access    public
     * @param string $number    The telephone number
     * @param int $flags        Can be a combination of the following flags:
     *                              - <b>VALIDATE_AU_PHONE_STRICT</b>: if
     *                                supplied then no spaces, parenthesis or dashes (-)
     *                                will be removed.
     *                              - <b>VALIDATE_AU_PHONE_NATIONAL</b>: when supplied
     *                                valid national numbers (eg. 03 9999 9999) will return true.
     *                              - <b>VALIDATE_AU_PHONE_INDIAL</b>: when supplied
     *                                valid indial numbers (eg. 13/1300/1800/1900) will return true.
     *                              - <b>VALIDATE_AU_PHONE_INTERNATIONAL</b>: when supplied
     *                                valid internation notation of Australian numbers 
     *                                (eg. +61.3 9999 9999) will return true.
     * @return    bool
     * 
     * @author Alex Hayes <ahayes@wcg.net.au>
     * @author Daniel O'Connor <daniel.oconnor@gmail.com>
     * 
     * @todo Check that $flags contains a valid flag.
     */
    function phoneNumber($number, $flags = VALIDATE_AU_PHONE_NATIONAL)
    {

        if(!($flags & VALIDATE_AU_PHONE_STRICT)) {
            $number = str_replace(
                array('(', ')', '-', ' '), 
                '', 
                $number
            );
        }

        if($flags & VALIDATE_AU_PHONE_NATIONAL) {
             $preg[] = "(0[23478][0-9]{8})";
        }

        if($flags & VALIDATE_AU_PHONE_INDIAL) {
             $preg[] = "(13[0-9]{4}|1[3|8|9]00[0-9]{6})";
        }

        if($flags & VALIDATE_AU_PHONE_INTERNATIONAL) {
             $preg[] = "(\+61\.[23478][0-9]{8})";
        }

        if(is_array($preg)) {
            $preg = implode("|", $preg);
            return preg_match("/^" . $preg . "$/", $number) ? true : false;
        }

        return false;

    }


    /**
     * Validate an Australian Company Number (ACN)
     *
     * @access  public
     * @param   string  $acn    ACN to validate
     * @return  bool    Returns true on success, false otherwise
     */
    function acn($acn)
    {
        $digits = array();
        
        $acn = str_replace(
            array('(', ')', '-', '+', '.', ' '),
            '',
            trim($acn)
        );
        
        if (!ctype_digit($acn) || strlen($acn) != 9) {
            return false;
        }
        
        $digits = str_split($acn);
        $sum = 0;
        
        //Apply weighting to digits 1 to 8
        for ( $i = 0; $i < 8; $i++) {
            $sum += $digits[$i]*(8-$i);
        }

        //Divide by 10 to obtain remainder
        $remainder = $sum%10;

        if ($remainder == 0) {
            $complement = 0 - $remainder;
        } else {
            //Complement the remainder to 10
            $complement = 10 - $remainder;
        }
        //$complement == last digit?
        return ($digits[8] == $complement);
    }

    /**
     * Social Security Number.
     *
     * Australia does not have a social security number system,
     * the closest equivalent is a Tax File Number.
     *
     * @static
     * @access  public
     * @see     Validate_AU::tfn()
     * @param   $input  Input to validate
     * @return  bool    Returns true on success, false otherwise
     */
    function ssn($input)
    {
        return Validate_AU::tfn($input);
    }

    /**
     * Tax File Number (TFN)
     *
     * Australia does not have a social security number system,
     * the closest equivalent is a Tax File Number.
     *
     * @access  public
     * @param   $tfn    Tax File Number
     * @return  bool    Returns true on success, false otherwise
     */
    function tfn($tfn)
    {
        $digits = array();
        $weights = array(1, 4, 3, 7, 5, 8, 6, 9, 10);

        $tfn = str_replace(
            array('(', ')', '-', '+', '.', ' '),
            '',
            $tfn
        );

        if (strlen($tfn) < 8 || 
            strlen($tfn) > 9 || !ctype_digit($tfn)) {
            return false;
        }

        $digits = str_split($tfn);
        $sum = 0;
        
        foreach ($digits as $key => $digit) {
            $sum += $digit*$weights[$key];
        }
        
        $remainder = $sum%11;
        return !( $remainder > 0 );
    }

    /**
     * Validate an Australian Business Number (ABN).
     *
     * You may optionally utilise an instance of Services_ABR to
     * strictly validate input.
     *
     * @static
     * @access  public
     * @param   Services_ABR    $client An instance of a Services_ABR object with an api key set.
     * @param   string          $abn    ABN to validate
     * @param   bool            $strict Optionally do a strict check with a web service.
     *                          If you provide this param, you must provide the third param ($client).
     * @return  bool            true on success, otherwise false or PEAR_Error
     */
    function abn($abn, $strict = false, $client = null)
    {
        $digits = array();
        $weights = array(10, 1, 3, 5, 7, 9, 11, 13, 15, 17, 19);

        //Strip blanks
        $abn = str_replace(array('(', ')', '-', '+', '.', ' '), '', $abn);

        if (strlen($abn) != 11 || !ctype_digit($abn)) {
            return false;
        }

        $digits = str_split($tfn);
        $digits[0]--;
        $sum = 0;
        
        //Apply weighting factor
        foreach ($digits as $key => $digit) {
            $sum += $digit*$weights[$key];
        }

        //Divide the total by 89 and get remainder
        $remainder = $sum%89;

        if ( $remainder > 0 ) {
            return false;
        } else {

            if ($strict) {
                if (is_a($client,'Services_ABR')) {
                    $result = $client->ABRSearchByABN($abn);

                    if (PEAR::isError($result)) {
                        return $result;
                    } else {
                        return true;
                    }
                } else {
                    return PEAR::raiseError("Missing or invalid param provided");
                }
            }

            return true;
        }

    }
}
?>
