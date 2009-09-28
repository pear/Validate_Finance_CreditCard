<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Financial functions for validation and calculation
 *
 * PHP Versions 4 and 5
 *
 * This source file is subject to version 3.01 of the PHP license,
 * that is bundled with this package in the file LICENSE, and is
 * available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt
 * If you did not receive a copy of the PHP license and are unable to
 * obtain it through the world-wide-web, please send a note to
 * license@php.net so we can mail you a copy immediately.
 *
 * @category  Validate
 * @package   Validate_Finance
 * @author    Stephan Jakoubek <stephan-pear@jakoubek.de>
 * @author    Uli Honal <uli@netzgeist.de> 
 * @copyright 1997-2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   SVN: $Id$
 * @link      http://pear.php.net/package/Validate_Finance
 */

/**
 * Validation function for ISINs
 * (International Securities Identification Numbers)
 *
 * @category  File_Formats
 * @package   Validate_Finance
 * @author    Stephan Jakoubek <stephan-pear@jakoubek.de>
 * @author    Uli Honal <uli@netzgeist.de> 
 * @copyright 1997-2009 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://pear.php.net/package/Validate_Finance
 */
class Validate_Finance_ISIN
{
    /**
     * Validate an ISIN (International Securities Identification Number, ISO 6166)
     *
     * @param string $isin ISIN to be validated
     *
     * @access public
     * @return boolean true if ISIN is valid
     */
    function validate($isin)
    {
        // Formal check.
        if (!preg_match('/^[A-Z]{2}[A-Z0-9]{9}[0-9]$/i', $isin)) {
            return false;
        }

        // Convert letters to numbers.
        $base10 = '';
        for ($i = 0; $i <= 11; $i++) {
            $base10 .= base_convert($isin{$i}, 36, 10);
        }

        // Calculate double-add-double checksum.
        $checksum = 0;
        $len      = strlen($base10) - 1;
        $parity   = $len % 2;
        // Iterate over every digit, starting with the rightmost (=check digit).
        for ($i = $len; $i >= 0; $i--) {
            // Multiply every other digit by two.
            $weighted = $base10{$i} << (($i - $parity) & 1);
            // Sum up the weighted digit's digit sum.
            $checksum += $weighted % 10 + (int)($weighted / 10);
        }

        return !(bool)($checksum % 10);
    } // end func Validate_Finance_ISIN

} // end class Validate_Finance_ISIN
