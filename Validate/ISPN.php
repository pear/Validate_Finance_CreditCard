<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Piotr Klaban <makler@man.torun.pl>                          |
// |          Damien Seguy <dams@nexen.net>                               |
// +----------------------------------------------------------------------+
//
// $Id$

/**
 * ISPN is also known as International Standard Product Numbers
 */

require_once 'Validate.php';

class Validate_ISPN
{
    /**
     * Validate a ISBN number
     * The ISBN is a unique machine-readable identification number, 
     * which marks any book unmistakably. 
     *
     * This function checks given number according
     *
     * @access public
     * @param  string  $isbn number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Damien Seguy <dams@nexen.net>
     */
    function isbn($isbn)
    {
        if (preg_match("/[^0-9 IXSBN-]/", $isbn)) {
            return false;
        }

        if (!ereg("^ISBN", $isbn)){
            return false;
        }

        $isbn = ereg_replace('-', '', $isbn);
        $isbn = ereg_replace(' ', '', $isbn);
        $isbn = eregi_replace('ISBN', '', $isbn);
        if (strlen($isbn) != 10) {
            return false;
        }
        if (preg_match("/[^0-9]{9}[^0-9X]/", $isbn)){
            return false;
        }

        $t = 0;
        for ($i = 0; $i < strlen($isbn) - 1; $i++){
            $t += $isbn[$i]*(10-$i);
        }
        $f = $isbn[9];
        if ($f == 'X') {
            $t += 10;
        } else {
            $t += $f;
        }
        if ($t % 11) {
            return false;
        }
        return true;
    }


    /**
     * Validate an ISSN (International Standard Serial Number)
     *
     * This function checks given ISSN number
     * ISSN identifies periodical publications:
     * http://www.issn.org
     *
     * @access public
     * @param  string  $issn number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function issn($issn)
    {
        static $weights_issn = array(8,7,6,5,4,3,2);

        $issn = strtoupper($issn);
        $issn = eregi_replace('ISSN', '', $issn);
        $issn = str_replace(array('-', '/', ' ', "\t", "\n"), '', $issn);
        $issn_num = eregi_replace('X', '0', $issn);

        // check if this is an 8-digit number
        if (!is_numeric($issn_num) || strlen($issn) != 8) {
            return false;
        }

        return Validate::_checkControlNumber($issn, $weights_issn, 11, 11);
    }

    /**
     * Validate a ISMN (International Standard Music Number)
     *
     * This function checks given ISMN number (ISO Standard 10957)
     * ISMN identifies all printed music publications from all over the world
     * whether available for sale, hire or gratis--whether a part, a score,
     * or an element in a multi-media kit:
     * http://www.ismn-international.org/
     *
     * @access public
     * @param  string  $ismn ISMN number
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function ismn($ismn)
    {
        static $weights_ismn = array(3,1,3,1,3,1,3,1,3);

        $ismn = eregi_replace('ISMN', '', $ismn);
        $ismn = eregi_replace('M', '3', $ismn); // change first M to 3
        $ismn = str_replace(array('-', '/', ' ', "\t", "\n"), '', $ismn);

        // check if this is a 10-digit number
        if (!is_numeric($ismn) || strlen($ismn) != 10) {
            return false;
        }

        return Validate::_checkControlNumber($ismn, $weights_ismn, 10, 10);
    }


    /**
     * Validate a EAN/UCC-8 number
     *
     * This function checks given EAN8 number
     * used to identify trade items and special applications.
     * http://www.ean-ucc.org/
     * http://www.uc-council.org/checkdig.htm
     *
     * @access public
     * @param  string  $ean number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function ean8($ean)
    {
        static $weights_ean8 = array(3,1,3,1,3,1,3);
        return Validate_ISPN::_process($ean, 8, $weights_ean8);
    }

    /**
     * Validate a EAN/UCC-13 number
     *
     * This function checks given EAN/UCC-13 number used to identify
     * trade items, locations, and special applications (e.g., coupons)
     * http://www.ean-ucc.org/
     * http://www.uc-council.org/checkdig.htm
     *
     * @access public
     * @param  string  $ean number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function ean13($ean)
    {
        static $weights_ean13 = array(1,3,1,3,1,3,1,3,1,3,1,3);
        return Validate_ISPN::_process($ean, 13, $weights_ean13);
    }

    /**
     * Validate a EAN/UCC-14 number
     *
     * This function checks given EAN/UCC-14 number
     * used to identify trade items.
     * http://www.ean-ucc.org/
     * http://www.uc-council.org/checkdig.htm
     *
     * @access public
     * @param  string  $ean number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function ean14($ean)
    {
        static $weights_ean14 = array(3,1,3,1,3,1,3,1,3,1,3,1,3);
        return Validate_ISPN::_process($ean, 14, $weights_ean14);
    }

    /**
     * Validate a UCC-12 (U.P.C.) ID number
     *
     * This function checks given UCC-12 number used to identify
     * trade items, locations, and special applications (e.g., * coupons)
     * http://www.ean-ucc.org/
     * http://www.uc-council.org/checkdig.htm
     *
     * @access public
     * @param  string  $ucc number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function ucc12($ucc)
    {
        static $weights_ucc12 = array(3,1,3,1,3,1,3,1,3,1,3);
        return Validate_ISPN::_process($ucc, 12, $weights_ucc12);
    }

    /**
     * Validate a SSCC (Serial Shipping Container Code)
     *
     * This function checks given SSCC number
     * used to identify logistic units.
     * http://www.ean-ucc.org/
     * http://www.uc-council.org/checkdig.htm
     *
     * @access public
     * @param  string  $sscc number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function sscc($sscc)
    {
        static $weights_sscc = array(3,1,3,1,3,1,3,1,3,1,3,1,3,1,3,1,3);
        return Validate_ISPN::_process($sscc, 18, $weights_sscc);
    }

    /**
     * Does all the work for EAN8, EAN13, EAN14, UCC12 and SSCC
     * 
     * @access private
     * @param int $data number (only numeric chars will be considered)
     * @param int $lenght required length of number string
     * @param array $weights holds the weight that will be used in calculations for the validation
     * @return bool    true if number is valid, otherwise false
     */     
    function _process($data, $length, &$weights)
    {
        //$weights = array(3,1,3,1,3,1,3,1,3,1,3,1,3,1,3,1,3);
        //$weights = array_slice($weights, 0, $length);

        $data = str_replace(array('-', '/', ' ', "\t", "\n"), '', $data);

        // check if this is a 18-digit number
        if (!is_numeric($data) || strlen($data) != $length) {
            return false;
        }

        return Validate::_checkControlNumber($data, $weights, 10, 10);
    }
}
?>