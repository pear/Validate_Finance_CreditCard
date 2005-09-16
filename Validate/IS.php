<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * Specific validation methods for data used in the UK
 *
 * PHP versions 4
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Validate
 * @package    Validate_IS
 * @author     Hannes Magnusson <bjori@php.net>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
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
 * @author     Hannes Magnusson <bjori@php.net>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
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
        if (!is_numeric($ssn)) {
            $ssn = str_replace(array(" ", "-"), "", $ssn);
        }
        if (strlen($ssn) != 10) {
            return false;
        }
        
        $tmp = array();
        preg_match_all("([0-9]{2})", $ssn, $tmp);
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
        if ($varTala == 11) {
            $varTala = 0;
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
     * @param     bool    optional; strong checks (default)
     * @return    bool
     */
    function postalCode($postcode, $strong = true)
    {
        /* Sanity check, all Icelanidc postalcodes are between 101 and 950 */
        if ($postcode > 100 && $postcode < 950 && !$strong) {
            return true;
        }
        
        if ($strong) {
            /*
             * Live check
             * $url = "http://www.postur.is/Gogn/Gotuskra/postnumer.txt";
             * $fp = tmpfile();
             * $contents = file_get_contents($url);
             * $int = fputs($fp, $contents);
             * fseek($fp, 0);
             */
            
            $file = "@DATADIR@/Validate_IS/IS_postcodes.txt";
            if (file_exists($file)) {
                if (is_writable($file) && filemtime($file) < time()-60*60*24*30) {
                    $url = "http://www.postur.is/Gogn/Gotuskra/postnumer.txt";
                    $fp = fopen($file, "r+");
                    $contents = file_get_contents($url);
                    fputs($fp, $contents);
                    fseek($fp, 0);
                }
                else {
                    $fp = fopen($file, "r");
                }
            }
            
            if (isset($fp) && is_resource($fp)) {
                while (false !== ($data = fgetcsv($fp, 128))) {
                    if ($data[0] == $postcode) {
                        fclose($fp);
                        return true;
                    }
                }
                fclose($fp);
            }
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
        $tel = str_replace(array("+", " ", "-"), array("00", "", ""), $tel);
        
        $telLength = strlen($tel);
        if ($telLength !=7) {
            if ($telLength != 12) {
                return false;
            }
            if (substr($tel, 0, 5) != "00354") {
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

