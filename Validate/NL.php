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
// | Authors: Dave Mertens <zyprexia@php.net>                             |
// +----------------------------------------------------------------------+
//
// $Id$
//
// Specific validation methods for data used in NL
//

require_once('Validate.php');

define( "VALIDATE_NL_PHONENUMBER_TYPE_ANY",     0);     //Any dutch phonenumber
define( "VALIDATE_NL_PHONENUMBER_TYPE_NORMAL",  1);     //only normal phonenumber (mobile numers are not allowed)
define( "VALIDATE_NL_PHONENUMBER_TYPE_MOBILE",  2);     //only mobile numbers are allowed

class Validate_NL
{
    /**
     * Validate a NL postcode
     *
     * @param   string  $postcode       NL postcode to validate
     * @param   bool    optional; strong checks (e.g. against a list of postcodes)
     * @return  bool    true if postcode is ok, false otherwise
     */
    function postcode($postcode, $strong=false)
    {
        // $strong is not used here at the moment; added for API compatibility
        // checks might be added at a later stage

        return (ereg('^[0-9]{4}\ {0,1}[A-Za-z]{2}$', $postcode)); // '1234 AB', '1234AB', '1234 ab'
    }

    /**
     * Validate a phonenumber
     *
     * @param   string  $number         Dutch phonenumber (can be in international format (eg +31 or 0031)
     * @param   int     $type           Type of phonenumber to check
     * @return  bool    true if (phone) number is correct
     */
    function phonenumber($number, $type = PHONENUMBER_TYPE_ANY)
    {
        $result = false;

        //we need at least 9 digits
        if (ereg("^[+0-9]{9,}$", $number)) {
            $number = substr($number, strlen($number)-9);

            //we only use the last 9 digits (so no troubles with international numbers)
            if (strlen($number) >= 9) {
                switch ($type)
                {
                    case VALIDATE_NL_PHONENUMBER_TYPE_ANY:
                        $result = true;     //we have a 9 digit numeric number.
                        break;
                    case VALIDATE_NL_PHONENUMBER_TYPE_NORMAL:
                        if ((int)$number[0] != 6)
                            $result = true;     //normal phonenumbers don't begin with 6 (00316, +316 and 06 are reserved for mobile numbers)
                        break;
                    case VALIDATE_NL_PHONENUMBER_TYPE_MOBILE:
                        if ((int)$number[0] == 6)
                            $result = true;     //mobilenumbers start with a 6
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
     * @param   string  $number     Dutch social security number
     * @return  bool    true if SSN number is correct
     */
    function ssn($ssn)
    {
        return (ereg("^[0-9]{9}$", $ssn));
    }

    /**
     * Bankaccount validation check (based on 11proef)
     *
     * @param   string  $number     Dutch bankaccount number
     * @return  bool    true is bankaccount number 'seems' correct
     */
    function bankAccountNumber($number)
    {
        $result     = false;        //by default we return false
        $checksum   = 0;

        if (is_numeric((string)$number) && strlen((string)$number) <= 10) {
            $number = str_pad($number, 10, '0', STR_PAD_LEFT);  //make sure we have a 10 digit number

            //create checksum
            for ($i=0; $i < 10; $i++) {
                $checksum += ( (int)$number[$i] * (10 - $i) );
            }

            //Banknumber is 'correct' if we can divide checksum by 11
            if ($checksum > 0 && $checksum % 11 == 0)
                $result = true;

            //return result
            return $result;
        }
    }

}
?>
