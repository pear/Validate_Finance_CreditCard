<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Specific validation methods for data used in Iceland
 *
 * PHP Versions 4 and 5
 *
 * This source file is subject to the New BSD license, That is bundled
 * with this package in the file LICENSE, and is available through
 * the world-wide-web at
 * http://www.opensource.org/licenses/bsd-license.php
 * If you did not receive a copy of the new BSDlicense and are unable
 * to obtain it through the world-wide-web, please send a note to
 * pajoye@php.net so we can mail you a copy immediately.
 *
 * @category  Validate
 * @package   Validate
 * @subpackage Validate_IS
 * @author    Hannes Magnusson <bjori@php.net>
 * @copyright 2005-2006  Hannes Magnusson
 * @license   http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_IS
 */

/**
 * Data validation class for Iceland
 *
 * This class provides methods to validate:
 *  - SSN (Social Security Number (Icelandic: kennitala))
 *  - Postal code (Icelandic: post numer)
 *  - Address (Icelandic: heimilisfang)
 *  - Telephone number (Icelandic: simanumer)
 *
 * @category  Validate
 * @package   Validate
 * @subpackage Validate_IS
 * @author    Hannes Magnusson <bjori@php.net>
 * @copyright 2005-2006  Hannes Magnusson
 * @license   http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Validate_IS
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
     *       (passed as integer) will NOT get validated!
     *
     * @param string $ssn SSN
     *
     * @access    public
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
     * @param int    $postCode the postcode to be validated
     * @param bool   $strong   optional; check against the official list 
     *                         (default off)
     * @param string $dataDir  optional; /path/to/data/dir
     * @param string $url      optional; http://domain.tld/path/to/live/data/file.txt
     *
     * @access    public
     * @return    bool
     */
    function postalCode($postCode, $strong = false,
                        $dataDir = null,
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

        if (empty($dataDir)) {
            $paths = array('@DATADIR@/Validate_IS', dirname(dirname(__FILE__)) . '/data');

            foreach ($paths as $path) {
                if (file_exists($path)) {
                    $dataDir = $path;
                }
            }
        }
        
        $dataFile = $dataDir . "/IS_postcodes.txt";
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
            $lastFile  = $dataFile;
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
     * @param string $address  Address to validate
     * @param int    $postcode Optional; check if address exists in that area
     * @param string $dataDir  Optional; /path/to/data/dir
     *
     * @return mixed            false on failure
     *                          array on in the form of: 
     *                          array(array("nf" => $nf, 
     *                                      "thgf" => $thgf, 
     *                                      "pnr" => $postnumer))
     *                          on success.
     */
    function address($address, $postcode = null, $dataDir = '')
    {
        static $lastFile;
        static $lastPos = -1;
        static $lastData = array();
        
        /* Sanity checks */
        if (!is_string($address)) {
            return false;
        }
        if (!empty($postcode)) {
            $postcode = (string)$postcode;
        }
        if (!empty($dataDir) && !is_readable($dataDir. "/IS_gotuskra.txt")) {
            return false;
        }
        
        if (ctype_digit($postcode)) {
            $rsl = Validate_IS::postalCode($postcode, false, $dataDir);
            if (!$rsl) {
                return false;
            }
        }
        
        if (empty($dataDir)) {
            $paths = array('@DATADIR@/Validate_IS', dirname(dirname(__FILE__)) . '/data');

            foreach ($paths as $path) {
                if (file_exists($path)) {
                    $dataDir = $path;
                }
            }
        }

        $file          = $dataDir. '/IS_gotuskra.txt';
        
        if ($file != $lastFile) {
            /* Reset cache */
            $lastData = array();
            $lastPos  = -1;
            $lastFile = "";
        }
        $lastFile = $file;
        
        $fp = fopen($file, 'r');
        if (!$fp) {
            return false;
        }
        if ($lastPos>0) {
            fseek($fp, $lastPos);
        }

        $lastCount = count($lastData);
        $i         = 0;
        $return    = array();
        reset($lastData);
        while (false !== ($data = ($lastPos == 0 || $lastCount>$i) ? 
            next($lastData) : fgetcsv($fp, 128, ';'))) {
            if ($i >= $lastCount) {
                $lastData[$i] = $data;
            }
            /* $data = array(0=>key, 1=>pnr 2=>nf 3=>thgf */
            if (strcasecmp($address, $data[2]) === 0 || 
                strcasecmp($address, $data[3]) === 0) {
                $return[] = array("nf" => $data[2], 
                                  "thgf" => $data[3], 
                                  "pnr" => $data[1]);
                if ($postcode && $postcode == $data[1]) {
                    $lastPos = ftell($fp);
                    /* In case we found matching address which didnt match 
                     * the postcode we'll return the last found 
                     */
                    return array(current($return));
                }
            }
            $i++;
        }
        $lastPos = 0;
        fclose($fp);

        if (!$postcode && count($return)) {
            return $return;
        }
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
     * @param string $number the telephone number
     *
     * @access    public
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
