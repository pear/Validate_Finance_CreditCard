<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
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
// | Authors: Jacques Marneweck <jacques@php.net>                         |
// +----------------------------------------------------------------------+
//
// $Id$
//
// Specific validation methods for data used in ZA

class Validate_ZA
{
    /**
     * Validate a South African Postal Code
     *
     * I've downloaded a list of postal codes from the SAPO website and
     * reduced the list down to unique postal codes.
     *
     * @static
     * @access  public
     * @param   string  the postal code to validate
     * @param   bool    optional; strong checks (e.g. against a list of postcodes)
     * @return  bool    true if postal code is ok else false
     * @link    http://www.sapo.co.za/cms/download/postcodes.zip
     */
    function postalCode($postcode, $strong = false)
    {
        if (!is_numeric($postcode)) {
            return false;
        }

        if ($strong) {
            static $postcodes;

            if (!isset($postcodes)) {
                $file = '@DATADIR@/Validate_ZA/ZA_postcodes.txt';
                $postcodes = array_map('trim', file($file));
            }
            return in_array((int)$postcode, $postcodes);
        }
        return (bool)ereg('^[0-9]{4}$', $postcode);
    }

    /**
     * Validates a "region" (i.e. province) code
     *
     * @static
     * @param   string  2-letter province code
     * @return  bool    true if valid else false
     * @access  public
     */
    function region($region)
    {
        switch (strtoupper($region)) {
            case 'EC': /* Eastern Cape */
            case 'FS': /* Free State */
            case 'GP': /* Gauteng */
            case 'KN': /* Kwa-Zulu Natal */
            case 'MP': /* Mpumalanga */
            case 'NC': /* Northern Cape */
            case 'NP': /* Limpopo (former Northern Province) */
            case 'NW': /* North West */
            case 'WC': /* Western Cape */
                return true;
                break;
            default:
                return false;
        }
        return (false);
    }                                                                              
}
?>
