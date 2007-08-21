<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Specific validation methods for data used in Austria
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
 * @package   Validate_AT
 * @author    Michael Wallner <mike@php.net>
 * @author    Byron Adams <byron.adams54@gmail.com> 
 * @copyright 1997-2005 Michael Wallner
 * @copyright 2006 Byron Adams
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_AT
 */



/**
 * Data validation class for Austria
 *
 * This class provides methods to validate:
 *  - Social insurance number (Sozialversicherungsnummer)
 *  - Postal code (Postleitzahl)
 *  - Regional code (Regionaler Code) 
 *
 * @category  Validate
 * @package   Validate_AT
 * @author    Michael Wallner <mike@php.net>
 * @author    Byron Adams <byron.adams54@gmail.com> 
 * @copyright 1997-2005 Michael Wallner
 * @copyright 2006 Byron Adams
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Validate_AT
 */

class Validate_AT
{
    /**
     * Validate postcode ("Postleitzahl")
     *
     * @param string $postcode postcode to validate
     * @param bool   $strong   optional; strong checks against a list of postcodes
     * @param string $dataDir  optional; name of directory datafile can be 
     *                         found in. set during install process but may be
     *                         overridden.
     *
     * @static   $postcodes
     * @access   public
     * @return   bool    true if postcode is ok, false otherwise
     */
    function postalCode($postcode, $strong = false, $dataDir = null)
    {
        if ($strong) {
            static $postcodes;

            if (!isset($postcodes)) {
                if ($dataDir != null && (is_file($dataDir . '/AT_postcodes.txt'))) {
                    $file = $dataDir . '/AT_postcodes.txt';
                } else {
                    $file = '@DATADIR@/Validate_AT/AT_postcodes.txt';
                }
                $postcodes = array_map('trim', file($file));
            }

            return in_array((int)$postcode, $postcodes);
        }
        return preg_match("(^[0-9]{4}$)", $postcode);
    }

    /**
     * Validate SSN ("Sozialversicherungsnummer")
     *
     * @param string $ssn the SSN/SVN number to validate
     *
     * @return   bool    true if SVN is ok, false otherwise
     * @access   public
     */
    function ssn($ssn)
    {
        $weights = array("3", "7", "9", "0", "5", "8", "4", "2", "1", "6");
        
        $lssn   = preg_replace("/[^\d]/", "", trim($ssn));
        $digits = str_split($lssn);
        
        $sum = 0;
        
        foreach ($digits as $key => $digit) {
            $sum += $digit * $weights[$key];
        }
        
        return ($sum % 11 == $lssn{3});
    }
    
    /**
     * Validates Austrian Regional Code ("Regionaler Code")
     *
     * The validation is based on FIPS 10-4 region codes.
     * http://en.wikipedia.org/wiki/FIPS_10-4
     * 
     * @param string $region regional code to validate
     *
     * @access    public
     * @return    bool        true if regional code is ok, false otherwise
     * @link      en.wikipedia.org/wiki/List_of_FIPS_region_codes_(A-C)#AU:_Austria
     */
    function region($region)
    {
        $region = str_ireplace(array("AU", "-", " "), "", $region);
        
        if (!ctype_digit($region)) {
            return false;
        }
        
        return ($region > 0 && $region < 10);
    }
}
