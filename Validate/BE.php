<?php

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
 * @package    Validate_BE
 * @author     Christophe Gesché <moosh@php.net>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_BE
 */
//require_once 'PEAR.php';
require_once 'Validate.php';

/**
 *
 * @constant VALIDATE_BE_PHONENUMBER_TYPE_ANY 
 * @constant VALIDATE_BE_PHONENUMBER_TYPE_NORMAL 
 * @constant VALIDATE_BE_PHONENUMBER_TYPE_MOBILE 
 * 
 */
define('VALIDATE_BE_PHONENUMBER_TYPE_ANY',     0);     //Any belgian phonenumber
define('VALIDATE_BE_PHONENUMBER_TYPE_NORMAL',  1);     //only normal phonenumber (mobile numers are not allowed)
define('VALIDATE_BE_PHONENUMBER_TYPE_MOBILE',  2);     //only mobile numbers are allowed
define('VALIDATE_BE_BANKCODE_MODULUS', 97);
define('VALIDATE_BE_SSN_MODULUS', 97);
define('VALIDATE_BE_VAT_MODULUS', 97);

/**
 * Data validation class for Belgium
 *
 * This class provides methods to validate:
 *  - Postal code
 *  - Belgian bank code
 *
 * @category   Validate
 * @package    Validate_BE
 * @author     Christophe Gesché <moosh@php.net>
 * @copyright  2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release:@package    version@
 * @link       http://pear.php.net/package/Validate_BE
 */
class Validate_BE
{
    /**
    * Validate a Belgian social security number
    *
    * The belgian social security number is on the SIS card of all belgian.
    * 
    * A check digit is the last one, computed the standard
    * _get_control_number function.
    *
    * @static
    * @access   public
    * @param    string  ssn to validate
    * @return   bool    true on success
    */
    function ssn($ssn)
    {
        // RULE 1 : 11 digit.
        if (!(bool) ereg('^[0-9]{11}$', $ssn)) {
            return false;
        }


        // RULE 2 : ssn begin with a reversed date
        $year   = substr($ssn,0,2);
        $month  = substr($ssn,2,2);
        $day    = substr($ssn,4,2);
        $number = substr($ssn,0,9);
        $check  = substr($ssn,9,2);

        /**
         * Check that the date is valid
         */
        if (!Validate::date("$year-$month-$day", array('format' => '%y-%m-%d'))) {
            return false;
        }

        // RULE 3 check is 97 - modulo 97 of all or RULES 3BIS
        if ((97 - Validate::_modf($number, VALIDATE_BE_SSN_MODULUS)) == $check) {
            return true;
        }

        // RULE 3 BIS
        // if RULE 3 check faild, prepend 2 to the number and retry
        if ((97 - Validate::_modf(2000000000 + $number, VALIDATE_BE_SSN_MODULUS)) == $check) {
            return true;
        }

        return false;
    }

    /**
     * Validate a Belgian postcode
     *
     * @param   string  postcode to validate
     * @param   bool    optional; strong checks (e.g. against a list of postcodes)
     * @return  bool    true if postcode is ok, false otherwise
     */
    function postalCode($postcode, $strong = false)
    {
        $postcode = ltrim( ltrim( strtolower( $postcode ), 'b'), '-' );
        if ($strong) {
            static $postcodes;

            if ( ! isset($postcodes) ) {
                $file = '@DATADIR@/Validate_BE/BE_postcodes.txt';
                if ( file_exists( $file ) ) {
                    foreach ( file( $file ) as $line ) {
                        $postcodes[] = substr( $line, 0, 4 );
                    }
                }
                else {
                    return false;
                }

            }

            //if (!is_array($postcodes)) return false; // pearerror DATA FILE NOT FOUND

            return (bool) ereg( '^[1-9][0-9]{3}$', $postcode) &&  in_array((int) $postcode, $postcodes) ;
        }
        return (bool) ereg( '^[1-9][0-9]{3}$', $postcode);
    }

    /**
     * Validate a Belgian bank account number
     *
     * Belgian bankcodes consist of
     *  - 3-figure number for the bank socity
     *  - 7-figure number for the account number
     *  - 2-figure number for mod 97
     *
     * @param   string  $bankcode       Belgian bankcode to validate
     * @return  bool    true if bankcode is ok, false otherwise
     */
    function bankcode( $bankcode )
    {
        if ((bool) ereg( '^[0-9]{3}[ ./-]?[0-9]{7}[ ./-]?[0-9]{2}$', $bankcode ) ) {
            $bankcode = str_replace( ' ', '', strtr( $bankcode, '/-.', '   ' ) );
            $num = substr( $bankcode, 0, 10);
            $checksum = substr( $bankcode, 10, 2);
            if ( $checksum == Validate::_modf( $num, VALIDATE_BE_BANKCODE_MODULUS ) ) {
                return true;
            }
            else {
                return false;
            }
        }
        else { 
            return false;
        }
        
    }


    /**
     * Validate a VAT account number
     *
     * Belgian VAT consist of
     *  - 3-figure number
     *
     * Actually no doc was found about a checksum
     *
     * @param   string  $vat        Belgian VAT to validate
     * @return  bool    true if VAT is ok, false otherwise
     */
    function vat($vat)
    {
        if ( (bool) ( ereg('^[0-9]{3}[ ./-]?[0-9]{3}[ ./-]?[0-9]{3}$', $vat ) ) ) {
            $vat = str_replace(' ', '', strtr($vat, '/-.', '   ' ) );
            if  (strlen($vat) == 9) {
                $number   = substr( $vat ,0, 7 );
                $checksum = substr( $vat ,7 ,2 );
                if (VALIDATE_BE_VAT_MODULUS - $checksum == (Validate::_modf($number, VALIDATE_BE_VAT_MODULUS ) ) ) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Validate a phonenumber
     * 065 12 34 56
     * 02 123 45 67
     * O485 12 34 56
     * 00 32 65 12 34 56
     * 00 32 2 123 45 67
     * 00 32 485 12 34 56
     * + 32 65 12 34 56
     * + 32 2 123 45 67
     * + 32 485 12 34 56
     * ++ 32 65 12 34 56
     * ++ 32 2 123 45 67
     * ++ 32 485 12 34 56
     *
     *
     * 010 Waver
     * 011 Hasselt
     * 012 Tongeren
     * 013 Diest
     * 014 Herentals, Turnhout
     * 015 Mechelen
     * 016 Leuven, Tienen
     * 019 Borgworm (Frans: Waremme)
     * 02  Brussel
     * 03  Antwerpen
     * 04  Luik
     * 050 Brugge, Zeebrugge
     * 051 Roeselare 
     * 052 Dendermonde
     * 053 Aalst
     * 054 Ninove
     * 055 Ronse
     * 056 Kortrijk, Komen-Waasten
     * 057 Ieper
     * 058 Veurne
     * 059 Oostende
     * 060 Chimay
     * 061 Libramont-Chevigny
     * 063 Aarlen
     * 064 La Louvière
     * 065 Bergen (Frans: Mons)
     * 067 Nijvel
     * 068 Ath
     * 069 Doornik
     * 071 Charleroi
     * 080 Stavelot
     * 081 Namen
     * 082 Dinant
     * 083 Ciney
     * 084 Marche-en-Famenne
     * 085 Hoei
     * 086 Durbuy
     * 087 Verviers
     * 089 Genk
     * 09  Gent

     * 0472-0479 mobiel (Proximus)
     * 0485-0486 mobiel (Base)
     * 0494-0499 mobiel (Mobistar)

     * 070 commercieel
     * 0800 gratis
     * 0900-0905 commercieel
     * 
     * NOTE : this validate want a BELGIAN phonenumber to return true,
     * not a valid number to call FROM belgium
     *
     * @param   string  $number         Belgian phonenumber (can be in international format (eg +32 or 0032)
     * @param   int     $type           Type of phonenumber to check / to attempt
     * @return  bool    true if (phone) number is correct
     * @see http://roamers.proximus.be/fr/Call_In_Belgium/CIB_AZ.html
     */
    function phoneNumber($phonenumber, $type = VALIDATE_BE_PHONENUMBER_TYPE_ANY)
    {

        $zoneprefixes['littlezone'] = array  ('010', '011', '012', '013', '014', '015', '016', '019', '050', '051', '052', '053', '054', '055', '056', '057', '058', '059', '060', '061', '063', '064', '065', '067', '068', '069', '071', '080', '081', '082', '083', '084', '085', '086', '087', '089');
        $zoneprefixes['bigzone'] = array  ('02', '03', '04', '09');
        $zoneprefixes['mobile']  = array('0472', '0473', '0474', '0475', '0476', '0477', '0478', '0479 ', '0485', '0486 ', '0494', '0495', '0496', '0497', '0498', '0499');

        $result = false;
        // Cleaning the phone number
        $phonenumber = trim($phonenumber);
        // international numba can begin with + or ++
        // If one, prepend a second
        if ($phonenumber[0]=='+' && $phonenumber[1]!='+' ) {
            $phonenumber = '+' . $phonenumber;
        }
        
        // if  phone number begin by a + replace it by 0
        // replace other non numerical  charater witha  blancspace
        // finaly remove all blank spaces.
        $phonenumber = str_replace(' ', '', strtr($phonenumber, '+/-.', '0   ' ) );

        
        // detect if it's a national or international numba
        if (substr($phonenumber, 0, 2) == '00') {
            //   $is_inter = TRUE;
            // International Number
            if (!substr($phonenumber, 2, 2) == '32') {
                // international number but not with belgian prefix
                // validate::failure ('not the international prefix');
                return false;
            }
            else {
                $phonenumber = str_replace('0032', '0', $phonenumber);
            }

        }

        // search national prefix of numba (mobile, big zone, little zone)
        $is_mobile = ( in_array(substr($phonenumber, 0, 4), $zoneprefixes['mobile'] ) );
        $in_bigzone = ( in_array(substr($phonenumber, 0, 2), $zoneprefixes['bigzone'] ) );
        $in_littlezone = ( in_array(substr($phonenumber, 0, 3), $zoneprefixes['littlezone'] ) );

        // if national prefix not detected, it's a bad number
        if ( ! $is_mobile && ! $in_bigzone && ! $in_littlezone) {
            return false; // wrong prefix
        }

        // try to accept as Type VALIDATE_BE_PHONENUMBER_TYPE_NORMAL (not mobile)
        if ($type == VALIDATE_BE_PHONENUMBER_TYPE_ANY || $type == VALIDATE_BE_PHONENUMBER_TYPE_NORMAL  ) {
            if( ! $is_mobile && strlen($phonenumber) == 9 && is_numeric($phonenumber)) {
                $result = true;     //we have a 9 digit numeric number.
            }
        }
        
        // try to accept as Type VALIDATE_BE_PHONENUMBER_TYPE_MOBILE
        if ($type == VALIDATE_BE_PHONENUMBER_TYPE_ANY || $type == VALIDATE_BE_PHONENUMBER_TYPE_MOBILE  ) {
            if( $is_mobile && strlen($phonenumber) == 10 && is_numeric($phonenumber)){
                $result = true;     //we have a 9 digit numeric number.
            }
        }
        
        //we need at least 9 digits
        if (ereg('^[+0-9]{9,}$', $phonenumber)) {
            $phonenumber = substr($phonenumber, strlen($phonenumber) - 10);
            //we only use the last 9 digits (so no troubles with international numbers)
            if (strlen($phonenumber) >= 9) {
                $result = false;
            }
        }
        return $result;
    }
}
?>