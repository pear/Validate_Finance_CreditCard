<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * Methods for common data validations
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
 * @package    Validate_DE
 * @author     Stefan Neufeind <pear.neufeind@speedpartner.de>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_DE
 */

/**
 * Data validation class for Germany
 *
 * This class provides methods to validate:
 *  - Postal code
 *  - German bank code
 *
 * @category   Validate
 * @package    Validate_DE
 * @author     Stefan Neufeind <pear.neufeind@speedpartner.de>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Validate_DE
 */
class Validate_DE
{
    /**
     * Validate a German postcode
     *
     * @param   string  postcode to validate
     * @param   bool    optional; strong checks (e.g. against a list of postcodes) (not implanted)
     * @return  bool    true if postcode is ok, false otherwise
     */
    function postalCode($postcode, $strong = false)
    {
        // $strong is not used here at the moment; added for API compatibility
        // checks might be added at a later stage

        return (bool)ereg('^[0-9]{5}$', $postcode);
    }

    /**
     * Validate a German bankcode
     *
     * German bankcodes consist of exactly 8 numbers
     *
     * @param   string  $postcode       German bankcode to validate
     * @return  bool    true if bankcode is ok, false otherwise
     */
    function bankcode($postcode)
    {
        return (bool)ereg('^[0-9]{8}$', $postcode);
    }
}
?>
