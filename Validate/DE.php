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
// Specific validation methods for data used in DE
//

require_once('Validate.php');

class Validate_DE
{
    /**
     * Validate a German postcode
     *
     * @param   string  postcode to validate
     * @param   bool    optional; strong checks (e.g. against a list of postcodes)
     * @return  bool    true if postcode is ok, false otherwise
     */
    function postcode($postcode, $strong=false)
    {
        // $strong is not used here at the moment; added for API compatibility
        // checks might be added at a later stage

        return (ereg('^[0-9]{5}$', $postcode));
    }

    /**
     * Validate a German bankcode
     *
     * German bankcodes consist of exactly 8 numbers
     *
     * @param   string  $postcode       German bankcode to validate
     * @return  bool    true if bankcode is ok, false otherwise
     */
    function bankcode($postcode)
    {
        return (ereg('^[0-9]{8}$', $postcode));
    }
}
?>
