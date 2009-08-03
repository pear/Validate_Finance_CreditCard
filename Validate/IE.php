<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Data validation class for Ireland
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
 * @package   Validate_IE
 * @author    David Coallier <davidc@php.net>
 * @copyright 1997-2008 Agora Production (http://agoraproduction.com)
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_IE
 */

/**
 * Data validation class for Ireland
 *
 * This class provides methods to validate:
 *  - Postal code
 *
 * @category  Validate
 * @package   Validate_IE
 * @author    David Coallier <davidc@php.net>
 * @author    Ken Guest      <ken@linux.ie>
 * @copyright 1997-2008 Agora Production (http://agoraproduction.com)
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Validate_IE
 */
class Validate_IE
{
    // {{{ public function swift
    /**
     * Validate an Irish SWIFT code
     *
     * @param string $swift swift code
     *
     * @return bool   true if number is valid, false if not.
     * @static
     * @access public
     */
    function swift($swift)
    {
        return preg_match('/^[a-z0-9]{4}IE[a-z0-9]{2}$/i', $swift);
    }
    // }}}
    // {{{ public function IBAN
    /**
     * Validate Irish IBAN
     *
     * @param string $iban  The account number to be validated
     * @param string $swift swift code to compare against IBAN
     *
     * @return    bool
     * @access  public
     */
    function IBAN($iban, $swift = false)
    {
        if ($swift) {
            $swift = substr($swift, 0, 4);
            if (substr($iban, 4, 4) != $swift) {
                return false;
            }
        }

        if (substr($iban, 0, 2) == 'IE') {
            include_once 'Validate/Finance/IBAN.php';
            return Validate_Finance_IBAN::validate($iban);
        }

        return false;
    }
    // }}}
    // {{{ public function phoneNumber
    /**
     * Validate an irish phone number
     *
     * This function validates an irish phone number.
     * You can either use the requiredAreaCode or not.
     * by default this is set to true.
     *
     * @param string $number           The phone number
     * @param bool   $requiredAreaCode defaults to true - to require area code checks
     *
     * <code>
     * <?php
     * // Include the package
     * require_once('Validate/IE.php');
     *
     * $phoneNumber = '+353 1 213 4567';
     * if ( Validate_IE::phoneNumber($phoneNumber) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     * $phoneNumber = '213 4567';
     * if ( Validate_IE::phoneNumber($phoneNumber, false) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     *
     * ?>
     * </code>
     *
     * @access public
     * @return bool   true if number is valid, false if not.
     * @static
     */
    function phoneNumber($number, $requiredAreaCode = true)
    {
        /*
         * categorize prefixes into landline, mobile and 'other'
         * each prefix has an associated regular expression.
         * use defaultRegExp if associated entry is empty.
         * @note Irish phone numbers are not the same as UK Phone numbers;
         *       Irish phone numbers are much less complex as UK now has per
         *       district phone numbers etc.
         */
        static $defaultRegExp = '/^\d{7,10}$/';
        static $irishLandLine = array(
                '1'=>'/^01\d{7}$/',
                '21'=>'', '22'=>'', '23'=>'', '24'=>'', '25'=>'', '242'=>'',
                '225'=>'', '26'=>'', '27'=>'', '28'=>'', '29'=>'', '402'=>'',
                '404'=>'', '405'=>'', '41'=>'', '42'=>'', '43'=>'', '44'=>'',
                '45'=>'', '46'=>'', '47'=>'',
                '48'=>'/^048[0-9]{8}$/', //direct dial to Northern Ireland
                '49'=>'', '51'=>'', '52'=>'', '53'=>'', '54'=>'', '55'=>'',
                '56'=>'', '57'=>'',
                '58'=>'/^058[0-9]{5}$/',
                '59'=>'/^059[0-9]{7}$/',
                '502'=>'', '504'=>'',
                '505'=>'/^0505[0-9]{5}$/',
                '506'=>'', '509'=>'', '61'=>'', '62'=>'', '63'=>'', '64'=>'',
                '65'=>'', '66'=>'', '67'=>'', '68'=>'', '69'=>'', '71'=>'',
                '74'=>'',
                '818'=>'/^0818[0-9]{6}$/',
                '90'=>'', '91'=>'', '92'=>'', '93'=>'', '94'=>'', '95'=>'',
                '96'=>'', '97'=>'', '98'=>'', '99'=>'');
        static $irishMobileAreas = array('83'=>'/^083[0-9]{7}$/',
                                           '85'=>'/^085[0-9]{7}$/',
                                           '86'=>'/^086[0-9]{7}$/',
                                           '87'=>'/^087[0-9]{7}$/',
                                           '88'=>'/^088[0-9]{7}$/',
                                           '89'=>'/^089[0-9]{7}$/');
        static $irishMobileAreasVoiceMail = array('83'=>'/^0835[0-9]{7}$/',
                                           '85'=>'/^0855[0-9]{7}$/',
                                           '86'=>'/^0865[0-9]{7}$/',
                                           '87'=>'/^0875[0-9]{7}$/',
                                           '88'=>'/^0885[0-9]{7}$/',
                                           '89'=>'/^0895[0-9]{7}$/');
        static $irishOtherRates = array('1800'=>'/^1800[0-9]{6}$/',
                                          '1850'=>'/^1850[0-9]{6}$/',
                                          '1890'=>'/^1890[0-9]{6}$/');

        if (preg_match('/^00.*$/', $number)) {
            $number = '+' . substr($number, 2);
        }
        $number = str_replace(array('(', ')', '-', '+', '.', ' '), '', $number);
        //remove country code for Ireland and insert leading zero of area code.
        //presence of area code is implied if country code is present.
        if (strpos($number, '353') === 0) {
            $number = "0" . substr($number, 3);
        }

        if (strlen(trim($number)) <= 0) {
            return false;
        }
        if (!ctype_digit($number)) {
            return false;
        }
        //area code must start with the standard 0 or a 1 for 'other rates'.
        if (($requiredAreaCode) && !(preg_match("(^[01][0-9]*$)", $number))) {
            return false;
        }
        //check special rate numbers
        if (($requiredAreaCode) && (substr($number, 0, 1) == '1')) {
            $prefix = substr($number, 0, 4);
            if (isset($irishOtherRates[$prefix])) {
                $reg = $irishOtherRates[$prefix];
                return (preg_match($reg, $number));
            } else {
                return false;
            }
        }

        $len = strlen($number);

        //if number has ten digits and a prefix it's likely a mobile phone
        if (($requiredAreaCode) && ($len == 10)) {
            $prefix = substr($number, 1, 2);
            if (isset($irishMobileAreas[$prefix])) {
                $regexp = $irishMobileAreas[$prefix];
                if (preg_match($regexp, $number)) {
                    return true;
                }
            }
        }
        //see if it's a mobile phone with a 'direct to voicemail' prefix.
        if (($requiredAreaCode) && ($len == 11)) {
            $prefix = substr($number, 1, 2);
            if (isset($irishMobileAreasVoiceMail[$prefix])) {
                $regexp = $irishMobileAreasVoiceMail[$prefix];
                if (preg_match($regexp, $number)) {
                    return true;
                }
            }
        }

        if (!$requiredAreaCode) {
            //regular numbers, without an area code, don't start with a zero.
            //they may be 5-8 digits long (depending on area code which can
            //be 2-4 digits long...)
            $preg = "/^[1-9]\d{4,7}$/";
            if (preg_match($preg, $number)) {
                return true;
            }
        } else {
            $ret = false;
            for ($i = 3; $i > 0; $i--) {
                $prefix = substr($number, 1, $i);
                $preg   = "";
                if (isset($irishLandLine[$prefix])) {
                    $preg = $irishLandLine[$prefix];
                    if ($preg == '') {
                        $preg = $defaultRegExp;
                    }
                    if (preg_match($preg, $number)) {
                        $ret = true;
                    }
                    break;
                }
            }
            if ($ret) {
                return true;
            }
        }

        return false;
    }
    // }}}
    // {{{ public function postalcode
    /**
     * Validate postal code
     *
     * This function validates postal district codes in Dublin.
     * It will be revised when national postal codes are rolled out.
     *
     * @param string $postalCode The postal code to validate
     * @param string $dir        optional; /path/to/data/dir
     *
     * @access public
     * @link   http://en.wikipedia.org/wiki/List_of_Dublin_postal_districts
     * @return bool    true if postcode is ok, false otherwise
     */
    function postalCode($postalCode, $dir = null)
    {
        $postalCode = strtoupper(str_replace(' ', '', trim($postalCode)));
        $postalCode = str_replace('DUBLIN', 'D', $postalCode);

        if ($dir != null && (is_file($dir . '/IE_postcodes.txt'))) {
            $file = $dir . '/IE_postcodes.txt';
        } else {
            $file = '@DATADIR@/Validate_IE/data/IE_postcodes.txt';
        }
        $postcodes = array_map('trim', file($file));
        return in_array($postalCode, $postcodes);
    }
    // }}}
    // {{{ public function passport
    /**
     * Validate passport
     *
     * Validate an irish passport number.
     *
     * @param string $pp The passport number to validate.
     *
     * @access public
     * @return bool   If the passport number is valid or not.
     */
    function passport($pp)
    {
        $pp   = strtolower($pp);
        $preg = "/^[a-z]{2}[0-9]{7}$/";

        if (preg_match($preg, $pp)) {
            return true;
        }

        return false;
    }
    // }}}
    // {{{ public function drive
    /**
     * Validates an Irish driving licence
     *
     * This function will validate the number on an Irish driving licence.
     *
     * @param string $dl The drivers licence to validate
     *
     * @access    public
     * @return    bool   true if it validates false if it doesn't.
     */
    function drive($dl)
    {
        $dl    = str_replace(array(' ', '-'), '', $dl);
        $preg  = "/^[0-9]{3}[0-9]{3}[0-9]{3}$/";
        $match = preg_match($preg, $dl) ? true : false;

        return $match;
    }
    // }}}
    // {{{ public function
    /**
     * Validate an Irish vehicle's license plate/registration number.
     *
     * @param string $number value to validate.
     *
     * @access public
     * @return bool   true on success; else false.
     */
    function licensePlate($number)
    {
        //in_array is case sensitive, so use strtoupper...
        $plate = strtoupper($number);
        $regex = "/^\d{2}[\ -]([A-Z][A-Z]?)[\ -]\d{1,6}$/";

        if (preg_match($regex, $plate, $matches)) {
            $mark = strtoupper($matches[1]);
            //check valid index mark
            $marks = array('C','CE','CN','CW','D','DL','G','KE','KK','KY','L',
                           'LD','LH','LK','LM','LS','MH','MN','MO','OY','RN',
                           'SO','TN','TS','W','WD','WH','WX','WW');
            return in_array($mark, $marks);
        } else {
            //two pre-1987 codes are still in use. ZZ and ZV.
            //format is ZZ nnnnn - 5 digits for ZZ code and as few as 4 for ZV
            $regex = "/^ZZ[\ -]\d{5}$/";
            if (preg_match($regex, $plate)) {
                return true;
            }
            $regex = "/^ZV[\ -]\d{4,5}$/";
            if (preg_match($regex, $plate)) {
                return true;
            }
            return false;
        }
    }
    // }}}
    // {{{ public function bankAC
    /**
     * Validate a bank account number
     *
     * This function will validate a bank account
     * number for irish banks.
     *
     * @param string $ac     The account number
     * @param string $noSort Don't validate the sort codes, optional (default: false)
     *
     * @access public
     * @return bool                    true if the account validates
     */
    function bankAC($ac, $noSort = false)
    {
        $ac   = str_replace(array('-', ' '), '', $ac);
        $preg = "/^\d{14}$/";

        if ($noSort) {
            $preg = "/^\d{8}$/";
        }

        $returnValue = preg_match($preg, $ac) ? true : false;

        return $returnValue;
    }
    // }}}
    // {{{ public function ssn
    /**
     * Validate SSN
     *
     * Ireland does not have a social security number system,
     * the closest equivalent is a Personal Public Service Number.
     *
     * @param string $ssn ssn number to validate
     *
     * @link http://en.wikipedia.org/wiki/Personal_Public_Service_Number
     * @access  public
     * @see     Validate_IE::ppsn()
     * @return  bool    Returns true on success, false otherwise
     */
    function ssn($ssn)
    {
        return Validate_IE::ppsn($ssn);
    }
    // }}}
    // {{{ public function checkMOD23
    /**
     * Return true if the checksum in the specified PPSN or vat number, without
     * the 'IE' prefix, is valid.
     *
     * @param string $value Value to perform modulus 23 checksum on.
     *
     * @access public
     * @return boolean
     */
    function checkMOD23($value)
    {
        $total = 0;
        for ($i = 0;$i<7;++$i) {
            $total += (int)$value[$i]*(8-$i);
        }
        $mod = ($total%23);
        if ($mod === 0) {
            $mod = 23;
        }
        return (int) (chr(64+$mod) == strtoupper($value[7]));

    }
    // }}}
    // {{{ public function ppsn
    /**
     * Personal Public Service Number
     *
     * Ireland does not have a social security number system,
     * the closest equivalent is a Personal Public Service Number.
     *
     * @param string $ppsn Personal Public Service Number
     *
     * @access  public
     * @return  bool    Returns true on success, false otherwise
     * @link    http://en.wikipedia.org/wiki/Personal_Public_Service_Number
     */
    function ppsn($ppsn)
    {
        $preg = "/^[0-9]{7}[A-Z]$/";

        if (preg_match($preg, $ppsn)) {
            //return (true);
            return Validate_IE::checkMOD23($ppsn);
        }

        $preg = "/^[0-9]{7}[A-Z][\ WTX]?$/";
        if (preg_match($preg, $ppsn)) {
            return Validate_IE::checkMOD23($ppsn);
        } else {
            return false;
        }
    }
    // }}}

    // {{{ public function vatNumber
    /**
     * Validate Irish VAT registration number.
     *
     * @param string $vat vat number to validate.
     *
     * @access  public
     *
     * <code>
     * <?php
     * // Include the package
     * require_once('Validate/IE.php');
     *
     * $vat = 'IE6335315A';
     * if ( Validate_IE::vatNumber($vat) ) {
     *     print 'Valid';
     * } else {
     *     print 'Not valid!';
     * }
     *
     * ?>
     * </code>
     *
     * @return  bool            Returns true on success, false otherwise
     * @link    http://www.iecomputersystems.com/ordering/eu_vat_numbers.htm
     * @link    http://www.braemoor.co.uk/software/vat.shtml
     */
    function vatNumber($vat)
    {
        // IE1234567X or IE1X34567X are valid (includes one or two letters
        // either the last or second + last).
        if (preg_match('/^IE\d{7}[a-z]$/i', $vat)) {
            return Validate_IE::checkMOD23(substr($vat, 2));
        } elseif (preg_match('/^IE\d[a-z]\d{5}[a-z]$/i', $vat)) {
            $d   = substr($vat, 2);
            $new = "0" . substr($d, 2, 5) . substr($d, 0, 1) . substr($d, 7, 1);
            return Validate_IE::checkMOD23($new);
        }
        return false;
    }
    // }}}
}
// }}}
?>
