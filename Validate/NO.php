<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Methods for validation in Norway
 *
 * PHP Version 5
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
 * @package   Validate_NO
 * @author    Christian Weiske <cweiske@php.net>
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   SVN: $Id: DE.php 242590 2007-09-16 17:35:37Z kguest $
 * @link      http://pear.php.net/package/Validate_DE
 */

/**
 * Data validation class for Norway
 *
 * This class provides methods to validate:
 *  - Postal code
 *
 * @category  Validate
 * @package   Validate_NO
 * @author    Christian Weiske <cweiske@php.net>
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Validate_DE
 */
class Validate_NO
{
    /**
     * Validate a Norway postcode.
     *
     * @param string $postcode postcode to validate
     * @param bool   $strong   optional; strong checks (e.g. against a list of 
     *                         postcodes) (not implemented)
     *
     * @return bool true if postcode is ok, false otherwise
     *
     * http://en.wikipedia.org/wiki/Postal_codes_in_Norway
     */
    public static function postalCode($postcode, $strong = false)
    {
        return (bool)preg_match('/^[0-9]{4}$/', $postcode);
    }

}
?>
