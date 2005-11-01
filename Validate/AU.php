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
// +----------------------------------------------------------------------+
//
/**
 * Specific validation methods for data used in Australia
 *
 * Contains code from Validate_AT and Validate_UK
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
 * @package    Validate_AU
 * @author     Daniel O'Connor <daniel.oconnor@gmail.com>
 * @author     Tho Nguyen <tho.nguyen@itexperts.com.au>
 * @copyright  1997-2005 Daniel O'Connor
 * @date       $Date$
 * @version    $Id$
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @link       http://www.getfridged.com/validate/
 */

/**
 * Requires Validate
 */
require_once 'Validate.php';

/**
 * Data validation class for Australia
 *
 * Contains code from Validate_AT and Validate_UK
 *
 * This class provides methods to validate:
 *  - Postal code
 *  - Phone number
 *  - Australian Business Number
 *  - Australian Company Number
 *  - Tax File Number
 *
 * @category   Validate
 * @package    Validate_AU
 * @author     Daniel O'Connor <daniel.oconnor@gmail.com>
 * @author     Tho Nguyen <tho.nguyen@itexperts.com.au>
 * @copyright  1997-2005 Daniel O'Connor
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @link       http://www.getfridged.com/validate/
 */
class Validate_AU
{

    /**
     * Validate postcode
     *
     * @static
     * @access   public
     * @param    string  $postcode  postcode to validate
     * @param    bool    $strong    optional; strong checks (e.g. against a list of postcodes)
     * @return   bool    true if postcode is ok, false otherwise
     */
    function postalCode($postcode, $strong = false)
    {
        if ($strong) {
            static $postcodes;

            if (!isset($postcodes)) {
                $file = '@DATADIR@/Validate_AU/AU_postcodes.txt';
                $postcodes = array_map('trim', file($file));
            }

            return in_array((int)$postcode, $postcodes);
        }
        return (bool)ereg('^[0-9]{4}$', $postcode);
    }


    /**
     * Validate a telephone number.
     *
     * Be aware, 1800 XXX XXX, 190X XXX XXX, 13XXXX numbers
     * are not catered for at this time.
     *
     * @static
     * @access    public
     * @param     string    $tel the tel number
     * @return    bool
     */
    function tel($tel)
    {
        $tel = str_replace(array('(', ')', '-', '+', '.', ' '), '', $tel);

        $preg = "/^0[23478][0-9]{8}$/";

        return (preg_match($preg, $tel)) ? true : false;
    }


    /**
     * Validate an Australian Company Number (ACN)
     *
     * @static
     * @access  public
     * @param   string  $acn    ACN to validate
     * @return  bool
     */
    function acn( $acn )
    {

        $digits = array();

        //Strip blanks
        $acn = str_replace(array('(', ')', '-', '+', '.', ' '), '', $acn);

        //Check if contains only digits
        if ( !is_numeric($acn) ) {
            return false;
        }

        //ABN has 9 digits in length
        if ( strlen($acn) != 9 ) {
            return false;
        }

        //Put each digit to an array
        for ( $i = 0; $i < strlen($acn); $i++ ) {
            $digits[] = $acn[$i];
        }

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
        if ( $digits[8] == $complement ) {
            return true;
        }

        return false;
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
     * @return  bool
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
     * @static
     * @access  public
     * @param   $tfn    Tax File Number
     * @return  bool
     */
    function tfn($tfn)
    {
        $digits = array();
        $weights = array(1, 4, 3, 7, 5, 8, 6, 9, 10);

        $tfn = str_replace(array('(', ')', '-', '+', '.', ' '), '', $tfn);

        //Check if contains only digits
        if ( !is_numeric($tfn) ) {
            settype($tfn, "int");
        }

        //Check length
        if ( strlen($tfn) < 8 || strlen($tfn) > 9 ) {
            return false;
        }

        //Put each digit to an array
        for ( $i = 0; $i < strlen($tfn); $i++ ) {
            $digits[] = $tfn[$i];
        }

        $sum = 0;
        //Apply weighting factor
        //11 Digits
        for ( $i = 0; $i < count($digits); $i++ ) {
            $sum += $digits[$i]*$weights[$i];
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
     *
     * @param   string          $abn    ABN to validate
     * @param   bool            $strict Optionally do a strict check with a web service.
     *                                  If you provide this param, you must provide the third param ($client).
     * @return  bool|PEAR_Error
     */
    function abn($abn, $strict = false, $client = null)
    {

        $digits = array();
        $weights = array(10, 1, 3, 5, 7, 9, 11, 13, 15, 17, 19);

        //Strip blanks
        $abn = str_replace(array('(', ')', '-', '+', '.', ' '), '', $abn);

        //Check if contains only digits
        if ( !is_numeric($abn) ) {
            settype($abn, "int");
            //return false;
        }

        //ABN has 11 digits in length
        if ( strlen($abn) != 11 ) {
            return false;
        }

        //Put each digit to an array
        for ( $i = 0; $i < strlen($abn); $i++ ) {
            $digits[] = $abn[$i];
        }

        //Subtract 1 from the first left digit
        $digits[0]--;

        $sum = 0;
        //Apply weighting factor
        //11 Digits
        for ( $i = 0; $i < count($digits); $i++ ) {
            $sum += $digits[$i]*$weights[$i];
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
