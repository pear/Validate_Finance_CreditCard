<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Ondrej Jombik <nepto@pobox.sk>                              |
// |         Philippe Jausions <Philippe.Jausions@11abacus.com>           |
// +----------------------------------------------------------------------+
//
// $Id$

class Validate_Finance_CreditCard
{
    /**
     * Validate a number according to Luhn check algorithm
     *
     * This function checks given number according Luhn check
     * algorithm. It is published on several places, also here:
     *
     * @link http://www.webopedia.com/TERM/L/Luhn_formula.html
     * @link http://www.merriampark.com/anatomycc.htm
     * @link http://hysteria.sk/prielom/prielom-12.html#3 (Slovak language)
     * @link http://www.speech.cs.cmu.edu/~sburke/pub/luhn_lib.html (Perl lib)
     *
     * @param  string  $number to check
     * @return bool    TRUE if number is valid, FALSE otherwise
     * @access public
     * @author Ondrej Jombik <nepto@pobox.sk>
     */
    function Luhn($number)
    {
        $len_number = strlen($number);
        $sum = 0;
        for ($k = $len_number % 2; $k < $len_number; $k += 2) {
            if ((intval($number{$k}) * 2) > 9) {
                $sum += (intval($number{$k}) * 2) - 9;
            } else {
                $sum += intval($number{$k}) * 2;
            }
        }
        for ($k = ($len_number % 2) ^ 1; $k < $len_number; $k += 2) {
            $sum += intval($number{$k});
        }
        return ($sum % 10) ? false : true;
    }


    /**
     * Validate a number according to Luhn check algorithm
     *
     * If a type is passed, the number will be checked against it.
     *
     * @param  string  $creditCard number (only numeric chars will be considered)
     * @param  string  $cardType card type ('visa', 'mastercard'...).
     *                 Case-insensitive
     * @return bool    TRUE if number is valid, FALSE otherwise
     * @access public
     * @see luhn()
     * @author Ondrej Jombik <nepto@pobox.sk>
     * @author Philippe Jausions <Philippe.Jausions@11abacus.com>
     */
    function creditCard($creditCard, $cardType = null)
    {
        $creditCard = preg_replace('/[^0-9]/', '', $creditCard);
        if (empty($creditCard) || ($len_number = strlen($creditCard)) <= 0) {
            return false;
        }

        // Only apply the Luhn algorithm for cards other than enRoute
        // So check if we have a enRoute card now
        if (strlen($creditCard) != 15
            || (substr($creditCard, 0, 4) != '2014'
                && substr($creditCard, 0, 4) != '2149')) {

            if (!Validate_CreditCard::Luhn($creditCard)) {
                return false;
            }
        }

        if (!is_null($cardType)) {
            return Validate_CreditCard::creditCardType($creditCard, $cardType);
        }

        return true;
    }


    /**
     * Validates the credit card number against a type
     *
     * @param string $creditCard credit card number to check
     * @param string $cardType Case-insensitive type (no spaces)
     * @return boolean TRUE is type matches, FALSE otherwise
     * @access public
     * @author Philippe Jausions <Philippe.Jausions@11abacus.com>
     * @link http://www.beachnet.com/~hstiles/cardtype.html
     */
    function creditCardType($creditCard, $cardType) {

        switch (strtoupper($cardType)) {
            case 'MASTERCARD':
                $regex = '/^5[1-5]\d{14}$/';
                break;
            case 'VISA':
                $regex = '/^4(\d{12}|\d{15})$/';
                break;
            case 'AMEX':
            case 'AMERICANEXPRESS':
                $regex = '/^3[47]\d{13}$/';
                break;
            case 'DINERS':
            case 'DINERSCLUB':
            case 'CARTEBLANCHE':
                $regex = '/^3(0[0-5]\d{11}|[68]\d{12})$/';
                break;
            case 'DISCOVER':
                $regex = '/^6011\d{12}$/';
                break;
            case 'JCB':
                $regex = '/^(3\d{15}|(2131|1800)\d{11})$/';
                break;
            case 'ENROUTE':
                $regex = '/^2(014|149)\d{11}$/';
                break;
            default:
                return false;
        }

        $creditCard = preg_replace('/[^0-9]/', '', $creditCard);
        return (bool)preg_match($regex, $creditCard);
    }
}
?>
