<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Specific validation methods for data used in Argentina
 *
 * This source file is subject to the New BSD license, That is bundled
 * with this package in the file LICENSE, and is available through
 * the world-wide-web at
 * http://www.opensource.org/licenses/bsd-license.php
 * If you did not receive a copy of the new BSDlicense and are unable
 * to obtain it through the world-wide-web, please send a note to
 * pajoye@php.net so we can mail you a copy immediately.
 *
 * PHP Versions 4 and 5
 *
 * @category  Validate
 * @package   Validate_AR
 * @author    Marcelo Santos Araujo <msaraujo@php.net>
 * @author    David Coallier <davidc@php.net>
 * @copyright 1997-2005  Marcelo Santos Araujo
 * @license   http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_AR
 */

/**
 * Data validation class for Argentina
 *
 * This class provides methods to validate:
 *  - Postal code CPA (Código Postal Argentino)
 *  - Regions - provinces of Argentina
 *
 * @category  Validate
 * @package   Validate_AR
 * @author    Marcelo Santos Araujo <msaraujo@php.net>
 * @author    David Coallier <davidc@php.net>
 * @copyright 1997-2005  Marcelo Santos Araujo
 * @license   http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Validate_AR
 */
class Validate_AR
{
    /**
     * Validate CPA (Código Postal Argentino, like postcode in US
     * and other languages)
     * format: one letter [B-T],four digits and three letters
     *
     * @param string $postalCode AR CPA/postalCode to validate
     * @param bool   $strong     optional; checks (e.g. against a list of postcodes)
     *                           (not implemented)
     * @param bool   $casesens   optional; (true - case sensitive, false - not case
     *                           sensitive)
     *
     * @return  bool    true if CPA is ok, false otherwise
     *
     * @link http://en.wikipedia.org/wiki/Postal_codes_in_Argentina
     * @link http://www.correoargentino.com.ar/consulta_cpa/cons_.php
     */
    function postalCode($postalCode, $strong = false, $casesens = true)
    {
        if (strlen($postalCode) == 8) {
            $regexp = $casesens ? '/^[B-T]\d{4}[A-Z]{3}$/' : '/^[B-T]\d{4}[A-Z]{3}$/i';
            return (bool) preg_match($regexp, $postalCode);
        } elseif (strlen($postalCode) == 5) {
            $regexp = $casesens ? '/^[B-T]\d{4}$/' : '/^[B-T]\d{4}$/i';
            return (bool) preg_match($regexp, $postalCode);
        } elseif (strlen($postalCode) == 4) {
            return (bool) preg_match('/^\d{4}$/', $postalCode);
        } else {
            return false;
        }
    }

    /**
     * Validates a "region" (i.e. province) code
     *
     * @param string $region 2-letter state code
     *
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
