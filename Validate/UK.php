<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Pierre-Alain Joye <paj@pearfr.org>                          |
// +----------------------------------------------------------------------+
//
// $Id$
//
// Specific validation methods for data used in UK
//

require_once('Validate.php');

class Validate_UK
{
    // overlay function
    function getZipValFunc(){
        return 'postcode';
    }
    /**
     * validates a standard uk postcode
     *
     *
     *
     * @access    public
     * @author    Michael Dransfield <mikeNO@SPAMblueroot.net>
     * @param     string $postcode the postcode to be validated
     * @return    bool
     */
    function postcode($postcode){
        // remove spaces and uppercase it
        $postcode = strtoupper(str_replace(' ', '', $postcode));

        $preg = "/^[A-Z]{1,2}[0-9]{1,2}[A-Z]?[0-9]{1,2}[A-Z]{2}/";
        $match = (preg_match($preg, $postcode))? true : false;
        return $match;
    }

    /**
     * validates a National Insurance Number (ssn equiv)
     *
     *
     *
     * @access    public
     * @author    Michael Dransfield <mikeNO@SPAMblueroot.net>
     * @param     string $ni NI number
     * @return    bool
     */
    function ni($ni){
        $ni = strtoupper(str_replace(' ', '', $ni));
        $preg = "/^[A-Z]{2}[0-9]{6}[ABCDEFT]/";
        $match = (preg_match($preg, $ni))? true : false;
        return $match;
    }

    /**
     * Validates a sort code, must be passed with dashes in the right places
     *
     *
     * @access    public
     * @author    Michael Dransfield <mikeNO@SPAMblueroot.net>
     * @param     string $sc the sort code
     * @return    bool
     * @see
     */
    function sortCode($sc){
        // must be in format nn-nn-nn (must contain dashes)
        // need to research the range of values - i have assumed 00-00-00 to 99-99-99
        // but it might be something like 01-01-01 to 50-99-99
        $preg = "/[0-9]{2}\-[0-9]{2}\-[0-9]{2}/";
        $match = (preg_match($preg, $sc))? true : false;
        return $match;
    }

    /**
     * Validates a bank ac number
     * do not use - it is too basic at the moment
     *
     * @access    public
     * @author    Michael Dransfield <mikeNO@SPAMblueroot.net>
     * @param     string $ac
     * @return    bool
     */
    function bankAC($ac){
        // just checking to see if it is 6-8 digits
        // *THIS IS PROBABLY WRONG!!! RESEARCH*
        $preg = "/[0-9]{6,8}/";
        $match = (preg_match($preg, $sc))? true : false;
        return $match;
    }

    /**
     * Checks that the entry is a number starting with 0 of the right length
     *
     *
     * @access    public
     * @author    Michael Dransfield <mikeNO@SPAMblueroot.net>
     * @param     string $tel the tel number
     * @return    bool
     * @see
     */
    function tel($tel){
        // just checks to see if it is numeric and starts with a 0
        // remove any wierd characters like (,),-,. etc
        $tel = str_replace(Array('(', ')', '-', '+', '.', ' '), '', $tel);
        $preg = "/^0[0-9]{8,10}/";
        $match = (preg_match($preg, $tel))? true : false;
        return $match;
    }

    /**
     * Validates a car registration number
     *
     *
     * @access    public
     * @author    Michael Dransfield <mikeNO@SPAMblueroot.net>
     * @param     string $reg the registration number
     * @return    bool
     */
    function carReg($reg){
        // checks for valid car licence plate
        // need to extend to include v old plates (without year prefix/suffix)
        // extend to reject invalid year letters (eg Z)
        // remove any spaces
        $reg = strtoupper(str_replace(' ', '', $reg));
        // suffixed year letter
        $suffpreg = "/[A-Z]{3}[0-9]{1,3}[A-Z]/";
        $suffres = preg_match($suffpreg, $reg);
        // prefixed year letter
        $prepreg = "/[A-Z][0-9]{1,3}[A-Z]{3}/";
        $suffres = preg_match($prepreg, $reg);
        // new ones
        $newpreg = "/[A-Z][0-9][05][A-Z]{3}/";
        $suffres = preg_match($newpreg, $reg);
        if (!$suffres||!$preres&&!$newres){
            return false;
        } else {
            return true;
        }
    }

    /**
     *
     * Validates a UK passport number, EU might be the same
     * just checks for 9 digits mine starts 00 and i have included that - might cause problems
     *
     * @access    public
     * @author    Michael Dransfield <mikeNO@SPAMblueroot.net>
     * @param     string $
     * @param     string $name
     * @return    string
     */
    function passport($pp){
        // just checks for 9 digit number
        $preg = "/[0-9]{9}/";
        $match = (preg_match($preg, $pp))? true : false ;
        return $match;
    }

    /**
     * Validates a UK driving licence
     *
     *
     * @access    public
     * @author    Michael Dransfield <mikeNO@SPAMblueroot.net>
     * @param     string $dl
     * @return    bool
     */
    function drive($dl){
        $preg = "[A-Z]{5}[0-9]{6}[A-Z0-9]{5}";
        $match = (preg_match($preg, $dl))? true : false;
        return $match;
    }
}

?>