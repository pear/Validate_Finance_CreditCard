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
     * Validates a number according to Luhn check algorithm
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
     * @static
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
     * Validates a credit card number
     *
     * If a type is passed, the card will be checked against it.
     * This method only checks the number locally. No banks or payment
     * gateways are involved.
     * This method doesn't guarantee that the card is legitimate. It merely
     * checks the card number passes a mathematical algorithm.
     *
     * @param string  $creditCard number (spaces and dashes tolerated)
     * @param string  $cardType type/brand of card (case insensitive)
     *               "MasterCard", "Visa", "AMEX", "AmericanExpress",
     *               "American Express", "Diners", "DinersClub", "Diners Club",
     *               "CarteBlanche", "Carte Blanche", "Discover", "JCB",
     *               "EnRoute".
     * @return bool   TRUE if number is valid, FALSE otherwise
     * @access public
     * @static
     * @see Luhn()
     */
    function number($creditCard, $cardType = null)
    {
        $cc = str_replace(array('-', ' '), '', $creditCard);
        if (($len = strlen($cc)) < 13
            || strspn($cc, '0123456789') != $len) {

            return false;
        }

        // Only apply the Luhn algorithm for cards other than enRoute
        // So check if we have a enRoute card now
        if (strlen($cc) != 15
            || (substr($cc, 0, 4) != '2014'
                && substr($cc, 0, 4) != '2149')) {

            if (!Validate_Finance_CreditCard::Luhn($cc)) {
                return false;
            }
        }

        if (!is_null($cardType)) {
            return Validate_Finance_CreditCard::type($cc, $cardType);
        }

        return true;
    }


    /**
     * Validates the credit card number against a type
     *
     * This method only checks for the type marker. It doesn't
     * validate the card number.
     *
     * @param string  $creditCard number (spaces and dashes tolerated)
     * @param string  $cardType type/brand of card (case insensitive)
     *               "MasterCard", "Visa", "AMEX", "AmericanExpress",
     *               "American Express", "Diners", "DinersClub", "Diners Club",
     *               "CarteBlanche", "Carte Blanche", "Discover", "JCB",
     *               "EnRoute".
     * @return bool   TRUE is type matches, FALSE otherwise
     * @access public
     * @static
     * @link http://www.beachnet.com/~hstiles/cardtype.html
     */
    function type($creditCard, $cardType)
    {
        switch (strtoupper($cardType)) {
            case 'MASTERCARD':
                $regex = '5[1-5][0-9]{14}';
                break;
            case 'VISA':
                $regex = '4([0-9]{12}|[0-9]{15})';
                break;
            case 'AMEX':
            case 'AMERICANEXPRESS':
            case 'AMERICAN EXPRESS':
                $regex = '3[47][0-9]{13}';
                break;
            case 'DINERS':
            case 'DINERSCLUB':
            case 'DINERS CLUB':
            case 'CARTEBLANCHE':
            case 'CARTE BLANCHE':
                $regex = '3(0[0-5][0-9]{11}|[68][0-9]{12})';
                break;
            case 'DISCOVER':
                $regex = '6011[0-9]{12}';
                break;
            case 'JCB':
                $regex = '(3[0-9]{15}|(2131|1800)[0-9]{11})';
                break;
            case 'ENROUTE':
                $regex = '2(014|149)[0-9]{11}';
                break;
            default:
                return false;
        }
        $regex = '/^' . $regex . '$/';

        $cc = str_replace(array('-', ' '), '', $creditCard);
        return (bool)preg_match($regex, $cc);
    }


    /**
     * Validates a card verification value format
     *
     * This method only checks for the format. It doesn't
     * validate that the value is the one on the card.
     *
     * CVV is also known as
     *  - CVV2 Card Validation Value 2 (Visa)
     *  - CVC  Card Validation Code (MasterCard)
     *  - CID  Card Identification (American Express and Discover)
     *  - CIN  Card Identification Number
     *  - CSC  Card Security Code
     *
     * Important information regarding CVV:
     *    If you happen to have to store credit card information, you must
     *    NOT retain the CVV after transaction is complete. Usually this
     *    means you cannot store it in a database, not even in an encrypted
     *    form.
     *
     * This method returns FALSE for card types that don't support CVV.
     *
     * @param string  $cvv value to verify
     * @param string  $cardType type/brand of card (case insensitive)
     *               "MasterCard", "Visa", "AMEX", "AmericanExpress",
     *               "American Express", "Discover"
     * @return bool   TRUE if format is correct, FALSE otherwise
     * @access public
     * @static
     */
    function cvv($cvv, $cardType)
    {
        switch (strtoupper($cardType)) {
            case 'MASTERCARD':
            case 'VISA':
            case 'DISCOVER':
                $digits = 3;
                break;
            case 'AMEX':
            case 'AMERICANEXPRESS':
            case 'AMERICAN EXPRESS':
                $digits = 4;
                break;
            default:
                return false;
        }

        if (strlen($cvv) == $digits
            && strspn($cvv, '0123456789') == $digits) {
            return true;
        }

        return false;
    }
}

?>