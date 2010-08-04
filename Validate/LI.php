<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Methods for validation in Liechtenstein
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
 * @package   Validate_LI
 * @author    Christian Weiske <cweiske@php.net>
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   SVN: $Id: DE.php 242590 2007-09-16 17:35:37Z kguest $
 * @link      http://pear.php.net/package/Validate_DE
 */

/**
 * Data validation class for Liechtenstein
 *
 * This class provides methods to validate:
 *  - Postal code
 *
 * @category  Validate
 * @package   Validate_LI
 * @author    Christian Weiske <cweiske@php.net>
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Validate_DE
 */
class Validate_LI
{
    /**
     * Validate a Liechtenstein postcode.
     * Liechtenstein uses the same codes as Switzerland.
     *
     * @param string $postcode postcode to validate
     * @param bool   $strong   optional; strong checks (e.g. against a list of 
     *                         postcodes) (not implemented)
     *
     * @return bool true if postcode is ok, false otherwise
     *
     * @link http://en.wikipedia.org/wiki/Postal_codes_in_Liechtenstein
     * @link http://www.geopostcodes.com/index.php?pg=browse&grp=0&niv=3&id=9&l=0&sort=1
     */
    public static function postalCode($postcode, $strong = false)
    {
        if (!$strong) {
            return (bool)preg_match('/^[0-9]{4}$/', $postcode);
        }

        //list is crom geopostcodes.com
        $codes = array(
            9485,
            9486,
            9487,
            9488,
            9489,
            9490,
            9491,
            9492,
            9493,
            9494,
            9495,
            9496,
            9486,
            9497,
            9498,
        );

        return in_array($postcode, $codes);
    }

}
?>
