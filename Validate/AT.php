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
// | Copyright (c) 2003 Michael Wallner <mike@iworks.at>                  |
// +----------------------------------------------------------------------+
//
// $Id$

/**
* Requires Validate
*/
require_once('Validate.php');

/**
* Validate_AT
*
* @author       Michael Wallner <mike@php.net>
* @package      Validate
* @category     PHP
*
* @version      $Revision$
* @access       public
*/
class Validate_AT
{
    /**
    * Validate postcode
    *
    * "Postleitzahl"
    *
    * @static
    * @access   public
    * @return   bool
    * @param    int     $zip
    */
    function postcode($zip)
    {
        static $postcodes;

        if (!isset($postcodes)) {
            $file = '@DATADIR@/Validate/AT_postcodes.txt';
            $postcodes = array_map('trim', file($file));
        }

        return in_array((int) $zip, $postcodes);
    }

    /**
    * Validate SSN
    *
    * "Sozialversicherungsnummer"
    *
    * @static
    * @access   public
    * @return   bool
    * @param    string  $svn
    */
    function ssn($svn)
    {
        $matched = preg_match(
            '/^(\d{3})(\d)\D*(\d{2})\D*(\d{2})\D*(\d{2})$/',
            $svn,
            $matches
        );

        if (!$matched) {
            return false;
        }

        list(, $num, $chk, $d, $m, $y) = $matches;

        if (!Validate::date("$d-$m-$y", array('format' => '%d-%m-%y'))) {
            return false;
        }

        $str = (string) $num . $chk . $d . $m . $y;
        $len = strlen($str);
        $fkt = '3790584216';
        $sum = 0;

        for ($i = 0; $i < $len; $i++) {
            $sum += $str{$i} * $fkt{$i};
        }

        $sum = $sum % 11;
        if ($sum == 10) {
            $sum = 0;
        }

        return ($sum == $chk);
    }
}
?>