<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2007  David Coallier                              |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
// | Author: David Coallier <davidc@php.net>                              |
// +----------------------------------------------------------------------+
//
/**
 * Methods for common data validations
 *
 * @category   Validate
 * @package    Validate_IE
 * @author     David Coallier <davidc@php.net>
 * @copyright  1997-2007 Agora Production (http://agoraproduction.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_IE
 */

/**
 * Data validation class for Ireland
 *
 * This class provides methods to validate:
 *  - Postal code
 *
 * @category   Validate
 * @package    Validate_IE
 * @author     David Coallier <davidc@php.net> 
 * @author     Ken Guest      <ken@linux.ie>
 * @copyright  1997-2007 Agora Production (http://agoraproduction.com)
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Validate_IE
 */
class Validate_IE
{
    // {{{ public function phoneNumber
    /**
     * Validate an irish phone number
     * 
     * This function validates an irish phone number.
     * You can either use the requiredAreaCode or not.
     * by default this is set to true.
     *
     * @note Irish phone numbers are not the same than UK Phone numbers
     *       Irish phone numbers are much less complex as UK now has per 
     *       district phone numbers etc.
     *
     * @access public
     * @param  string $number            The phone number
     * @param  bool   $requiredAreaCode  Default false If require area code checking
     * @return bool   true of number is valid, false if not. 
     * @static
     */
    function phoneNumber($number, $requiredAreaCode = true)
    {
        $number = str_replace(array('(', ')', '-', '+', '.', ' '), '', $number);

        if (strlen(trim($number)) <= 0) {
            return false;
        }

        if (!$requiredAreaCode) {
            $preg = "/^\d{7}$/";
            if (preg_match($preg, $number)) {
                return true;
            }
        } else {
            $preg = "/^\d{9,10}$/";
            if (preg_match($preg, $number)) {
                return true;
            }
        }

        return false;
    }
    // }}}
    // {{{ public function postalcode
    /**
     * Validate postal code
     *
     * This function validate postal codes in Ireland
     * @note There is not postal code in ireland, this is either
     *       a joke :) or a catch to catch people that are faking
     *       to be irish.
     *
     * @access public
     * @param  string $postalCode  The postal code to validate
     * @return bool   false.. there's not postal codes in Ireland.
     */
    function postalCode($postalCode)
    {
        if (strlen(trim($postalCode)) > 0) {
            return false;
        }
    }
    // }}}
    // {{{ public function passport
    /**
     * Validate passport
     *
     * Validate an irish passport number.
     *
     * @access public
     * @param  string $pp   The passport number to validate.
     * @return bool   If the passport number is valid or not.
     */
    function passport($pp) 
    {
        $pp   = strtolower($pp);
        $preg = "/^[a-z]{2}[0-9]{7}$/";

        if (preg_match($preg, $pp)) {
            return true;
        }

        return false;
    }
    // }}}
    // {{{ public function drive
    /**
     * Validates an Irish driving licence
     *
     * This function will validate the drivers
     * licence for irish licences.
     *
     * @access    public
     * @param     string $dl  The drivers licence to validate
     * @return    bool   true if it validates false if it doesn't.
     */
    function drive($dl)
    {
        $dl    = str_replace(array(' ', '-'), '', $dl);
        $preg  = "/^[0-9]{3}[0-9]{3}[0-9]{3}$/";
        $match = preg_match($preg, $dl) ? true : false;

        return $match;
    }
    // }}}
    // {{{ public function bankAC
    /**
     * Validate a bank account number
     *
     * This function will validate a bank account
     * number for irish banks.
     *
     * @access public
     * @param  string           $ac       The account number
     * @param  string  optional $noSort   Do not validate the sort codes (default: false)
     * @return bool                       true if the account validates
     */
    function bankAC($ac, $noSort = false)
    {
        $ac = str_replace(array('-', ' '), '', $ac);
        $preg = "/^\d{14}$/";

        if ($noSort) {
            $preg = "/^\d{8}$/";
        }

        $returnValue = preg_match($preg, $ac) ? true : false;

        return $returnValue;
    }
    // }}}
}
// }}}
?>
