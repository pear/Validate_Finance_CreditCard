<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Specific validation methods for data used in the Iran.
 *
 * PHP version 5
 *
 * This source file is subject to the New BSD license, 
 * That is bundled with this package in the file LICENSE, 
 * and is available through the world-wide-web at http://www.opensource.org/licenses/bsd-license.php 
 * If you did not receive a copy of the new BSDlicense and are unable to obtain it through the world-wide-web, 
 * please send a note to pajoye@php.net so we can mail you a copy immediately.
 * 
 * @category  Validate
 * @package   Validate_IR
 * @author    Arash Hemmat <arash.hemmat@gmail.com>
 * @copyright 2011 Arash Hemmat
 * @license   http://www.opensource.org/licenses/bsd-license.php  new BSD
 * @link      http://pear.php.net/package/Validate_IR
 */
class Validate_IR
{
    /**
     * Check for Persian/Farsi characters and number an zero width non-joiner space.
     * Also accpets latin numbers preventing potential problem until PHP becomes fully unicode compatible.
     *
     * @param string $check The value to check.
     *
     * @return boolean
     * @access public
     */
    function alphaNumeric($check) 
    {
        $pattern = '/[^\x{0600}-\x{06FF}\x{FB50}-\x{FDFD}\x{FE70}-\x{FEFF}\x{0750}-\x{077F}0-9\s\x{200C}]+/u';
        return !preg_match($pattern, $check);
    }

    /**
     * Checks for Persian/Farsi digits only and won't accept Arabic and Latin digits.
     *
     * @param string $check The value to check.
     *
     * @return boolean
     * @access public
     */
    function numeric($check) 
    {
        $pattern = '/[^\x{06F0}-\x{06F9}\x]+/u';
        return !preg_match($pattern, $check);
    }

    /**
     * Validation of Iran credit card numbers.
     *
     * @param string $check The value to check.
     *
     * @return boolean
     * @access public
     */
    function creditCard($check) 
    {
        $pattern = '/[0-9]{4}-?[0-9]{4}-?[0-9]{4}-?[0-9]{4}$/';
        return preg_match($pattern, $check);
    }

    /**
     * Checks phone numbers for Iran
     *
     * @param string $check The value to check.
     *
     * @return boolean
     * @access public
     */
    function phoneNumber($check) 
    {
        $pattern = '/^[- .\(\)]?((98)|(\+98)|(0098)|0){1}[- .\(\)]{0,3}[1-9]{1}[0-9]{1,}[- .\(\)]*[0-9]{3,8}[- .\(\)]?$/';
        return preg_match($pattern, $check);
    }

    /**
     * Checks mobile numbers for Iran
     *
     * @param string $check The value to check.
     *
     * @return boolean
     * @access public 0 9 124061351
     */
    function mobileNumber($check) 
    {
        $pattern = '/^[- .\(\)]?((98)|(\+98)|(0098)|0){1}[- .\(\)]{0,3}((91)|(93)){1}[0-9]{8}$/';
        return preg_match($pattern, $check);
    }

    /**
     * Checks postal codes for Iran
     *
     * @param string $check The value to check.
     *
     * @return boolean
     * @access public
     */
    function postalCode($check) 
    {
        $pattern = '/^\d{10}$/';
        return preg_match($pattern, $check);
    }

    /**
     * Checks social security numbers for Iran called "kode melli"
     *
     * @param string $check The value to check.
     *
     * @return boolean
     * @access public
     */
    function ssn($check) 
    {
        $pattern = '/^\d{10}$/';
        if (!preg_match($pattern, $check)) {
            return false;
        }

        $sum = 0;
        $equivalent = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += $check{$i} * (10 - $i);
            if ($check{1} == $check{$i}) {
                $equivalent++;
            }
        }

        if ($equivalent == 9) {
            return false;
        }

        $remaining = $sum % 11;

        if ($remaining <= 1) {
            return (bool)($remaining == $check{9});
        }

        return (bool)((11 - $remaining) == $check{9});
    }
}
