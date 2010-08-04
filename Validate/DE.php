<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Methods for common German data validations
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
 * @package   Validate_DE
 * @author    Stefan Neufeind <pear.neufeind@speedpartner.de>
 * @copyright 1997-2005 Stefan Neufeind
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_DE
 */

/**
 * Data validation class for Germany
 *
 * This class provides methods to validate:
 *  - Postal code
 *  - German bank code
 *
 * @category  Validate
 * @package   Validate_DE
 * @author    Stefan Neufeind <pear.neufeind@speedpartner.de>
 * @copyright 1997-2005 Stefan Neufeind
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Validate_DE
 */
class Validate_DE
{
    /**
     * Validate a German postcode
     *
     * @param string $postcode postcode to validate
     * @param bool   $strong   optional; strong checks (e.g. against a list of 
     *                         postcodes) (not implemented)
     *
     * @return  bool    true if postcode is ok, false otherwise
     */
    function postalCode($postcode, $strong = false)
    {
        // $strong is not used here at the moment; added for API compatibility
        // checks might be added at a later stage

        return (bool)preg_match('/^[0-9]{5}$/', $postcode);
    }

    /**
     * Validate a German bankcode
     *
     * German bankcodes consist of exactly 8 numbers
     *
     * @param string $bankcode German bankcode to validate
     *
     * @return  bool    true if bankcode is ok, false otherwise
     */
    function bankcode($bankcode)
    {
        return (bool)preg_match('/^[0-9]{8}$/', $bankcode);
    }
}
?>
