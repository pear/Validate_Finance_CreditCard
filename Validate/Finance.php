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
* @version     0.1
* @since       PHP 4.1.0
*/
class Validate_Finance {

    /**
     * Performs validation an IBAN (international bankaccoun number)
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

    // }}}

} // end class Validate_Finance
?>
