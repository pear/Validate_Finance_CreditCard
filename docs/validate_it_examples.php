<?php
/**
 * Specific validation methods for data used in Italy
 * 
 * PHP version 4
 * 
 * This source file is subject to the New BSD license, That is bundled
 * with this package in the file LICENSE, and is available through
 * the world-wide-web at http://www.opensource.org/licenses/bsd-license.php
 * If you did not receive a copy of the new BSDlicense and are unable
 * to obtain it through the world-wide-web, please send a note to
 * pajoye@php.net so we can mail you a copy immediately.
 *
 * @category  Validate
 * @package   Validate_IT
 * @author    Jacopo Andrea Nuzzi <jacopo.nuzzi@gmail.com>
 * @copyright 2008 Jacopo Andrea Nuzzi
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_IT
 */

require_once 'Validate/IT.php';

if (Validate_IT::region('lom')) {
    echo 'Valid region code.';
} else {
    echo 'NOT valid region code.';
}

if (Validate_IT::regionName('lombardia')) {
    echo 'Valid region name.';
} else {
    echo 'NOT valid region name.';
}

if (Validate_IT::postalCode('20126')) {
    echo 'Valid postal code (CAP).';
} else {
    echo 'NOT valid postal code (CAP).';
}


if (Validate_IT::city('mi')) {
    echo 'Valid city code.';
} else {
    echo 'NOT valid city code.';
}

if (Validate_IT::cityName('milano')) {
    echo 'Valid city name.';
} else {
    echo 'NOT valid city name.';
}

if (Validate_IT::driverLicense('XX00000000X')) {
    echo 'Valid driver license.';
} else {
    echo 'NOT valid driver license.';
}
if (Validate_IT::mobilePhoneNumber('3330000000')) {
    echo 'Valid mobile phone number.';
} else {
    echo 'NOT valid mobile phone number.';
}

if (Validate_IT::phoneNumberPrefix('+39 02/00000000')) {
    echo 'Valid phone number.';
} else {
    echo 'NOT valid phone number.';
}

if (Validate_IT::fiscalCode('xxx xxx 00a00 x000x')) {
    echo 'Valid fiscal code.';
} else {
    echo 'NOT valid fiscal code.';
}
?>