<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006  Hannes Magnusson                            |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
// | Author: Hannes Magnusson  <bjori@php.net>                            |
// +----------------------------------------------------------------------+
//
/**
 * Specific validation methods for data used in Iceland
 *
 * @category   Validate
 * @package    Validate
 * @subpackage Validate_IS
 * @author     Hannes Magnusson <bjori@php.net>
 * @copyright  2005-2006  Hannes Magnusson
 * @license    http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_IS
 */

/**
 * Data validation class for IS
 *
 * This class provides methods to validate:
 *  - SSN (Social Security Number (Icelandic: kennitala))
 *  - Postal code (Icelandic: post numer)
 *  - Telephone number (Icelandic: simanumer)
 *
 */
class Validate_IS
{
    /**
     * Validate Icelandic SSN (kennitolu)
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
            case 10: // "Hagstofan" has nothing to say what should happen here
                     // it looks like "vartalan" should be set to 0
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
     * Validates Icelandic postal codes (postnumer)
     *
     * Validates Icelandic postalcodes. By default checks against (prefetched)
     * list containing all Icelandic postalcodes.
     * Live check (against, by default, the official list) is available by
     * switching $strong (2nd parameter) to true.
     * $dataFile will be rewritten with the data retrived from $url in $strong mode
     *
     * User can provide his own datafile if he wishes and/or own "official" list.
     *
     * @access    public
     * @param     int     the postcode to be validated
     * @param     bool    optional; check against the official list (default off)
     * @param     string  optional; /path/to/data/file.txt
     * @param     string  optional; http://domain.tld/path/to/live/data/file.txt
     * @return    bool
     */
    function postalCode($postCode, $strong = false,
                        $dataFile = '@DATADIR@/Validate_IS/IS_postcodes.txt',
                        $url = 'http://www.postur.is/gogn/Gotuskra/postnumer.txt')
    {
        static $postCodes = array();
        static $lastUrl   = '';
        static $lastFile  = '';

        /* Sanity check, all Icelandic postalcodes are between 101 and 950 */
        $postCode = (int)$postCode;
        if ($postCode <= 100 || $postCode > 950) {
            return false;
        }

        /* Same configuration as last time? No need to go further then */
        if (count($postCodes) && $dataFile == $lastFile &&
           (!$strong || $lastUrl == $url)) {
            return in_array($postCode, $postCodes);
        }

        /* Live check */
        if ($strong) {
            $fp = fopen($url, 'r');
            if ($fp) {
                $postCodes = array();
                while (false !== ($data = fgetcsv($fp, 128, ';'))) {
                    $postCodes[] = $data[0];
                }
                unset($postCodes[0]); // Fake entry
                fclose($fp);

                if (is_writable($dataFile)) {
                    $fp = fopen($dataFile, 'w');
                    if ($fp) {
                        fwrite($fp, implode("\n", $postCodes));
                        fclose($fp);
                    }
                }
            }
        }

        if (!count($postCodes) && is_readable($dataFile)) {
            $postCodes = file($dataFile);
            $lastFile = $dataFile;
        }
        if (count($postCodes) && in_array($postCode, $postCodes)) {
            return true;
        }

        return false;
    }


    /**
     * Checks if given address exists
     * If postcode is provided, check if address exists in that area.
     *
     * NOTE: does *NOT* work completly, yet.
     * NOTE: $strong is *NOT* implimented, yet.
     *
     * @param string $address   Address to validate
     * @param int    $postcode  Optional; check if address exists in that area
     * @param bool   $strong    Optional; Live check
     * @param string $dataDir   Optional; /path/to/data/dir
     * @param string $url       Optional; http://domain.tld/path/to/data/file.txt
     * @return bool
     */
    function address($address, $postcode = null, $strong = false,
                     $dataDir = '', $url = '')
    {
        if (!$dataDir) {
            $dataDir = '@DATADIR@/Validate_IS';
        }
        if (!is_null($postcode)) {
            /* Shall we dare to call postalCode staticly? */
            if (isset($this)) {
                $rsl = $this->postalCode($postcode, $dataDir);
            } else {
                $rsl = self::postalCode($postcode, $dataDir);
            }
            if (!$rsl) {
                return false;
            }
        }
        $file = is_readable($dataDir.'/IS_gotuskra.txt') ?
            $dataDir. '/IS_gotuskra.txt' :
            '@DATADIR@/Validate_IS/IS_gotuskra.txt';

        $fp = fopen($file, 'r');
        if (!$fp) {
            return false;
        }

        $address = ucwords($address);
        while (false !== ($data = fgetcsv($fp, 128, ';'))) {
            /*
            * TODO:
            *   case-insensitive compare
            */
            if ($address == $data[2] || $address == $data[3]) {
                if (!$postcode) {
                    fclose($fp);
                    return true;
                }
                if ($postcode == $data[1]) {
                    fclose($fp);
                    return true;
                }
            }
        }
        fclose($fp);

        return false;
    }

    /**
     * Validates Icelandic telephone numbers (simanumer)
     * 
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
     * @param     string $number the telephone number
     * @return    bool
     */
    function phoneNumber($number)
    {
        /* Replace "+" with "00" and delete spaces/hyphens */
        $number = str_replace(array('+', ' ', '-'), array('00', '', ''), $number);

        /* Sanity check */
        if (!ctype_digit($number)) {
            return false;
        }
        
        /* Checks if the number is 7digits or 12 (prefixed with 00354) */
        $telLength = strlen($number);
        if ($telLength != 7) {
            if ($telLength == 12) {
                if (substr($number, 0, 5) != '00354') {
                    return false;
                }
            } else {
                return false;
            }
        }

        /* Get the first real telephone number digit and check if its valid */
        $firstDigit = substr($number, -7, 1);
        if (in_array($firstDigit, array(0, 1, 2, 3))) {
            return false;
        }

        return true;
    }
}
?>
