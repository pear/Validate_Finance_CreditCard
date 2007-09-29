<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Specific validation methods for data used in the UK
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
 * @package   Validate_UK
 * @author    Michael Dransfield <mikeNO@SPAMblueroot.net>
 * @author    Ian P. Christian <pookey@pookey.co.uk>
 * @author    Tomas V.V.Cox <cox@idecnet.com>
 * @author    Pierre-Alain Joye <pajoye@php.net>
 * @copyright 1997-2005 Michael Dransfield, Ian P. Christian, Pierre-Alain Joye
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_UK
 */

/**
 * Data validation class for the UK
 *
 * This class provides methods to validate:
 *  - SSN (National Insurance/NI Number)
 *  - Sort code
 *  - Bank account number
 *  - Postal code
 *  - Telephone number
 *  - Car registration number
 *  - Passport
 *  - Driving license
 *
 * @category  Validate
 * @package   Validate_UK
 * @author    Michael Dransfield <mikeNO@SPAMblueroot.net>
 * @author    Ian P. Christian <pookey@pookey.co.uk>
 * @copyright 1997-2005 Michael Dransfield, Ian P. Christian
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Validate_UK
 */
class Validate_UK
{
    /**
     * validates a postcode
     *
     * Validation according to the "UK Government Data Standards Catalogue"
     * Using PostCode-format version 2.1, which can be obtained from:
     * http://www.govtalk.gov.uk/gdsc/html/noframes/PostCode-2-1-Release.htm
     *
     * Note: The official validation-pattern was altered to also support postcodes
     * with none or even spaces at various places. We don't count spaces as being
     * "essential" for the validation-process.
     * It was also necessary to refactor the regex to make it usable for preg_match.
     *
     * @param string $postcode the postcode to be validated
     * @param bool   $strong   optional; strong checks (e.g. against a list
     *                         of postcodes) (not implanted)
     *
     * @access    public
     * @return    bool
     */
    function postalCode($postcode, $strong = false)
    {
        // $strong is not used here at the moment; added for API compatibility
        // checks might be added at a later stage

        // remove spaces and uppercase it
        $postcode = strtoupper(str_replace(' ', '', $postcode));
        $preg     = "/^([A-PR-UWYZ]([0-9]([0-9]|[A-HJKSTUW])?|[A-HK-Y][0-9]"
                  . "([0-9]|[ABEHMNPRVWXY])?)[0-9][ABD-HJLNP-UW-Z]{2}|GIR0AA)$/";
        $match    = preg_match($preg, $postcode) ? true : false;
        return $match;
    }

    /**
     * Validates a social security number whic in UK is
     * National Insurance Number or ni for short
     *
     * Validation according to the "UK Government Data Standards Catalogue"
     * Using NationalInsuranceNumber-format version 2.1, which can be obtained from:
     * www.govtalk.gov.uk/gdsc/html/noframes/NationalInsuranceNumber-2-1-Release.htm
     *
     * Note: The official validation-pattern was altered to also support numbers
     * with none or even spaces at various places. We don't count spaces as being
     * "essential"
     * for the validation-process.
     *
     * @param string $ssn NI number
     *
     * @access public
     * @return bool
     */
    function ssn($ssn)
    {
        // remove spaces and uppercase it
        $ssn  = strtoupper(str_replace(' ', '', $ssn));
        $preg = "/^[A-CEGHJ-NOPR-TW-Z][A-CEGHJ-NPR-TW-Z][0-9]{6}[ABCD]?$/";
        if (preg_match($preg, $ssn)) {
            $bad_prefixes = array('GB', 'BG', 'NK', 'KN', 'TN', 'NT', 'ZZ');
            return (array_search(substr($ssn, 0, 2), $bad_prefixes) === false);
        }
        return false;
    }

    /**
     * Validates a sort code, must be passed with dashes in the right places
     *
     * @param string $sc the sort code
     *
     * @access public
     * @return bool
     * @see
     */
    function sortCode($sc)
    {
        // must be in format nn-nn-nn (must contain dashes)
        // need to research the range of values - i have assumed 00-00-00 to 99-99-99
        // but it might be something like 01-01-01 to 50-99-99
        $preg  = "/^[0-9]{2}\-[0-9]{2}\-[0-9]{2}$/";
        $match = (preg_match($preg, $sc)) ? true : false;
        return $match;
    }

    /**
     * Validates a bank ac number
     *
     * do not use - it is too basic at the moment
     *
     * @param string $ac the account number
     *
     * @access public
     * @return bool
     */
    function bankAC($ac)
    {
        // just checking to see if it is 6-8 digits
        // FIXME *THIS IS PROBABLY WRONG!!! RESEARCH*
        // There is a modulus 10/11 system that could be implemented here, but
        // it's potentially quite complex
        // http://en.wikipedia.org/wiki/Luhn_formula - Ian
        $preg  = "/^[0-9]{6,8}$/";
        $match = (preg_match($preg, $ac)) ? true : false;
        return $match;
    }

    /**
     * Checks that the entry is a number starting with 0 of the right length
     *
     * @param string $number the tel number
     *
     * @access public
     * @return bool
     * @see
     */
    function phoneNumber($number)
    {
        //phone number can't include letters.
        if (preg_match("/[A-Z]/i", $number) != 0) {
            return false;
        }
        $number = preg_replace('/\D+/', '', $number);
        $len    = strlen($number);
        return $number[0] == 0 // first number is 0
            && (($len == 11 && ($number[1] != "0"))    // 11 digits is fine
            || ($len == 10     // 10 digits is fine if 01 or 08
                && ($number[1] == 1 || $number[1] == 8)));
    }

    /**
     * Validates a car registration number
     *
     * @param string $reg the registration number
     *
     * @access public
     * @return bool
     */
    function carReg($reg)
    {
        include_once 'Validate/UK/carReg.php';
        $reg = strtoupper(str_replace(array('-', ' '), '', $reg));
        // functions to check, in order
        $regFuncs = array(
            '2001',
            '1982',
            '1963',
            '1950',
            '1932',
            'Pre1932'
        );
        foreach ($regFuncs as $func) {
            $cfunc = 'validateVehicle' . $func;
            $ret   = $cfunc($reg);
            if ($ret !== false) {
                // maybe return something useful here when possible?
                return true;
            }
        }
        return false;
    }

    /**
     * Validates a UK passport number.
     *
     * EU might be the same just checks for 9 digits
     *
     * @param string $pp the passport number
     *
     * @access public
     * @return string
     */
    function passport($pp)
    {
        // just checks for 9 digit number
        return (ctype_digit($pp) && strlen($pp) == 9);
    }

    /**
     * Validates a UK driving licence
     *
     * @param string $dl the driving license
     *
     * @access public
     * @return bool
     */
    function drive($dl)
    {
        $dl    = strtoupper(str_replace(' ', '', $dl));
        $preg  = "/^[A-Z]{5}[0-9]{6}[A-Z0-9]{5}$/";
        $match = (preg_match($preg, $dl)) ? true : false;
        return $match;
    }
}
?>
