<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
//
//
// Specific validation methods for data used in DE
//

require_once('Validate.php');

class Validate_DE
{
    /**
     * Validate a DE postcode
     *
     * @param   string  $postcode       DE postcode to validate
     * @return  bool    true if postcode is ok, false otherwise
     */
    function postcode($postcode)
    {
        return (ereg('^[0-9]{5}$', $postcode));
    }
}
?>
