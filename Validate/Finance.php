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
// | Author: Stefan Neufeind <pear.neufeind@speedpartner.de>              |
// +----------------------------------------------------------------------+
//
// $Id$

require_once('Validate/Finance/IBAN.php');

/**
* Financial functions for validation and calculation
*
* @author      Stefan Neufeind <neufeind@speedpartner.de>
* @since       PHP 4.1.0
*/
class Validate_Finance {

    /**
     * Validation of an IBAN (international bankaccount number)
     *
     * @param     string      $iban              IBAN to be validated
     * @access    public
     * @since     0.1
     * @return    boolean   true if IBAN is okay
     */
    function iban($iban='')
    {
        return Validate_Finance_IBAN::validate($iban);
    } // end func iban

    /**
     * Validation of a Euro banknote id
     *
     * @param     string      $banknote              Euro banknote id to be validated
     * @access    public
     * @since     0.1
     * @return    boolean   true if Euro banknote id is okay
     */
    function banknoteEuro($banknote='')
    {
        $euro_countrycode = array('J', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        if (strlen($banknote) != 12) {
            return false;
        }
        if (!in_array($banknote[0], $euro_countrycode)) {
            return false;
        }
        
        // build checksum, preparation
        $banknote_replace_chars = range('A', 'Z');
        foreach (range(10,35) as $tempvalue) {
            $banknote_replace_values[]=strval($tempvalue);
        }

        // build checksum, substitute and calc
        $tempbanknote = str_replace($banknote_replace_chars, $banknote_replace_values, substr($banknote,0,-1));
        $tempcheckvalue = 0;
        for ($strcounter = 0; $strcounter < strlen($tempbanknote); $strcounter++) {
            $tempcheckvalue += intval($tempbanknote[$strcounter]);
        }
        $tempcheckvalue %= 9; // modulo 9
        $tempcheckvalue = 8 - $tempcheckvalue;

        return (intval($banknote[strlen($banknote)-1]) == $tempcheckvalue);
    } // end func banknoteEuro

} // end class Validate_Finance
?>
