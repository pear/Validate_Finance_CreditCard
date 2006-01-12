<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005  Marcelo Santos Araujo                       |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
// | Author: Marcelo Santos Araujo <msaraujo@php.net>                     |
// +----------------------------------------------------------------------+
//
/**
 * Specific validation methods for data used in Argentina
 *
 * @category   Validate
 * @package    Validate_AR
 * @author     Marcelo Santos Araujo <msaraujo@php.net>
 * @author     David Coallier <davidc@php.net>
 * @copyright  1997-2005  Marcelo Santos Araujo
 * @license    http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version
 * @link       http://pear.php.net/package/Validate_AR
 */

/**
 * Data validation class for Argentina
 *
 * This class provides methods to validate:
 *  - Postal code CPA (Código Postal Argentino)
 *  - Regions - provinces of Argentina
 * 
 * @category   Validate
 * @package    Validate_AR
 * @author     Marcelo Santos Araujo <msaraujo@php.net>
 * @author     David Coallier <davidc@php.net>
 * @copyright  1997-2005  Marcelo Santos Araujo
 * @license    http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Validate_AR
 */
class Validate_AR
{
    /**
     * Validate CPA (Código Postal Argentino, like postcode in US
     * and other languages)
     * format: one letter [B-T],four digits and three letters
     *
     * @param   string  $postalCode   AR CPA/postalCode to validate
     * @param   bool    optional; strong checks (e.g. against a list of postcodes) (not implanted)
     * @param   bool    optional; casesens (true - case sensitive, false - not case sensitive)
     * @return  bool    true if CPA is ok, false otherwise
     */
    function postalCode($postalCode, $strong = false, $casesens = true)
    {
        $regexp = $casesens ? '/^[B-T]{1}\d{4}[A-Z]{3}$/' : '/^[B-T]{1}\d{4}[A-Z]{3}$/i';
        return preg_match($regexp, $postalCode);
    }
    
    /**
     * Validates a "region" (i.e. province) code
     *
     * @param string $region 2-letter state code
     * @return bool  true if $region is ok, false otherwise
     * @static
     */
    function region($region)
    {
        switch (strtoupper($region)) {
            case 'BA':
            case 'CC':
            case 'CT':
            case 'CH':
            case 'DF':
            case 'CB':
            case 'CN':
            case 'ER':
            case 'FM':
            case 'JY':
            case 'LP':
            case 'LR':
            case 'MZ':
            case 'MN':
            case 'NQ':
            case 'RN':
            case 'SA':
            case 'SJ':
            case 'SL':
            case 'SC':
            case 'SF':
            case 'SE':
            case 'TF':
            case 'TM':
                return true;
        }
        return false;
    }


}
?>
