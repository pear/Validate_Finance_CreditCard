<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005  Hannes Magnússon                            |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
// | Author: Tomas V.V.Cox  <cox@idecnet.com>                             |
// |         Pierre-Alain Joye <pajoye@php.net>                           |
// +----------------------------------------------------------------------+
//
/**
 * Specific validation methods for data used in the IS
 *
 * @category   Validate
 * @package    Validate_IS
 * @author     Hannes Magnússon <bjori@php.net>
 * @copyright  1997-2005  Hannes Magnússon
 * @license    http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version    CVS: $Id$
 */

/**
 * Data validation class for IS
 *
 * This class provides methods to validate:
 *  - SSN (Social Security Number (Icelanidc: kennitala))
 *  - Postal code
 *  - Telephone number
 *
 * @category   Validate
 * @package    Validate_IS
 * @author     Hannes Magnússon <bjori@php.net>
 * @copyright  1997-2005  Hannes Magnússon
 * @license    http://www.opensource.org/licenses/bsd-license.php  new BSD
 */
class Validate_IS
{
    /**
     * Validates a social security number (kennitölu)
     *
     * Validation according to http://www.hagstofa.is/?PageID=1474
     *
     * Note: Icelandic SSN (Social Security Number) is on the form ddmmyy-xxxx.
     *       Here we strip the "-" char from the string (if present) and/or
     *       spaces in our match.
     * Note: PASS IN STRING, NO EXCEPTIONS! SSN with leading zero
     *       (passed as integer) will NOT get vallidated!
     *
     * @access    public
     * @param     string $ssn SSN
     * @return    bool
     */
    function ssn($ssn)
    {
        /* Sanity checks, Icelandic ssn are 10 numbers */
        if (!ctype_digit($ssn)) {
            $ssn = str_replace(array(' ', '-'), '', $ssn);
        }
        if (strlen($ssn) != 10 || !ctype_digit($ssn)) {
            return false;
        }

        $tmp = array();
        preg_match_all('([0-9]{2})', $ssn, $tmp);
        $kt = $tmp[0];
        /*
         * $kt[0] = date
         * $kt[1] = month
         * $kt[2] = year (2 letters!)
         */
        switch($ssn{9}) {
            case 0:
                $kt[2] += 2000;
                break;
            case 1:
                $kt[3] += 2100;
                break;
            case 8:
                $kt[2] += 1800;
                break;
            case 9:
                $kt[2] += 1900;
                break;
            default:
                return false;
        }

        if (!checkdate($kt[1], $kt[0], $kt[2])) {
            return false;
        }

        /* Calculate the nineth number (Icelandic: vartala) */
        $sum  = $ssn{7}*2;
        $sum += $ssn{6}*3;
        $sum += $ssn{5}*4;
        $sum += $ssn{4}*5;
        $sum += $ssn{3}*6;
        $sum += $ssn{2}*7;
        $sum += $ssn{1}*2;
        $sum += $ssn{0}*3;

        $varTala = 11 - ($sum % 11);
        switch($varTala) {
            case 10:
            case 11:
                $varTala = 0;
                break;
        }

        if ($ssn{8} != $varTala) {
            return false;
        }

        return true;
    }

    /**
     * validates a postcode
     *
     * Validates Icelandic postalcodes. By defaults checks against (prefetched)
     * csv list containing all Icelanidc postalcodes. If the list is one month
     * old, trys to update it.
     *
     * @access    public
     * @param     int     the postcode to be validated
     * @param     bool    optional; check against the official list (default off)
     * @return    bool
     */
    function postalCode($postcode, $strong = false)
    {
        /* Sanity check, all Icelandic postalcodes are between 101 and 950 */
        if ($postcode <= 100 || $postcode > 950) {
            return false;
        }

        $file = '@DATADIR@/Validate_IS/IS_postcodes.txt';

        $postCodes = array();
        if ($strong) {
            $url = 'http://www.postur.is/gogn/Gotuskra/postnumer.txt';

            $fp = fopen($url, 'r');
            if($fp) {
                while (false !== ($data = fgetcsv($fp, 128, ';'))) {
                    $postCodes[] = $data[0];
                }
                unset($postCodes[0]); // Fake entry
                fclose($fp);
                
                if(is_writable($file)) {
                    $fp = fopen($file, 'w');
                    if($fp) {
                        fwrite($fp, implode("\n", $postCodes));
                        fclose($fp);
                    }
                }
            }
        }

        if(!count($postCodes) && file_exists($file)) {
            $postCodes = file($file);
        }
        if (is_array($postCodes) && in_array($postcode, $postCodes)) {
            return true;
        }

        return false;
    }

    /**
     * Checks that the telephone number is 7digits and legal
     * home/office/gsm number (not information/emergency service etc.)
     *
     * NOTE: Number prefixed with 00354 or +354 are allowed
     *
     * Note: Icelandic telephone numbers are on the form xxx-xxxx.
     *       Here we strip the "-" char from the string (if present) and/or
     *       spaces in our match.
     *
     * @access    public
     * @param     string $tel the telephone number
     * @return    bool
     * @see
     */
    function tel($tel)
    {
        $tel = str_replace(array('+', ' ', '-'), array('00', '', ''), $tel);

        $telLength = strlen($tel);
        if ($telLength !=7) {
            if ($telLength != 12) {
                return false;
            }
            if (substr($tel, 0, 5) != '00354') {
                return false;
            }
        }

        $firstDigit = substr($tel, -7, 1); // Gets the first digit in the tel.
        if (in_array($firstDigit, array(0, 1, 2, 3))) {
            return false;
        }

        return true;
    }
}
?>
