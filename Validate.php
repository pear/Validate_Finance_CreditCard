<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Tomas V.V.Cox <cox@idecnet.com>                             |
// |          Pierre-Alain Joye <paj@pearfr.org>                          |
// +----------------------------------------------------------------------+
//
// $Id$
//
// Methods for common data validations
//

define('VALIDATE_NUM',          '0-9');
define('VALIDATE_SPACE',        '\s');
define('VALIDATE_ALPHA_LOWER',  'a-z');
define('VALIDATE_ALPHA_UPPER',  'A-Z');
define('VALIDATE_ALPHA',        VALIDATE_ALPHA_LOWER . VALIDATE_ALPHA_UPPER);
define('VALIDATE_EALPHA_LOWER', VALIDATE_ALPHA_LOWER . 'áéíóúàèìòùäëïöüâêîôûñç');
define('VALIDATE_EALPHA_UPPER', VALIDATE_ALPHA_UPPER . 'ÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛÑÇ');
define('VALIDATE_EALPHA',       VALIDATE_EALPHA_LOWER . VALIDATE_EALPHA_UPPER);
define('VALIDATE_PUNCTUATION',  VALIDATE_SPACE . '\.,;\:&"\'\?\!\(\)');
define('VALIDATE_NAME',         VALIDATE_EALPHA . VALIDATE_SPACE . "'");
define('VALIDATE_STREET',       VALIDATE_NAME . "/\\ºª");

class Validate
{
    /**
     * Validate a number
     *
     * @param string    $number     Number to validate
     * @param array     $options    array where:
     *                              'decimal'   is the decimal char or false when decimal not allowed
     *                                          i.e. ',.' to allow both ',' and '.'
     *                              'dec_prec'  Number of allowed decimals
     *                              'min'       minimun value
     *                              'max'       maximum value
     */
    function number($number, $options=array())
    {
        $decimal=$dec_prec=$min=$max= null;
        if(is_array($options)){
            extract($options);
        }

        $dec_prec   = $dec_prec ? "{1,$dec_prec}" : '+';
        $dec_regex  = $decimal  ? "[$decimal][0-9]$dec_prec" : '';

        if (!preg_match("|^[-+]?\s*[0-9]+($dec_regex)?\$|", $number)) {
            return false;
        }
        if ($decimal != '.') {
            $number = strtr($number, $decimal, '.');
        }
        $number = (float)str_replace(' ', '', $number);
        if ($min !== null && $min > $number) {
            return false;
        }
        if ($max !== null && $max < $number) {
            return false;
        }
        return true;
    }

    /**
     * Validate a email
     *
     * @param string    $email          URL to validate
     * @param boolean   $domain_check   Check or not if the domain exists
     */
    function email($email, $check_domain = false)
    {
        if($check_domain){

        }

        if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.
                 '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
                 '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email))
        {
            if ($check_domain && function_exists('checkdnsrr')) {
                list (, $domain)  = explode('@', $email);
                if (checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A')) {
                    return true;
                }
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Validate a string using the given format 'format'
     *
     * @param string    $string     String to validate
     * @param array     $options    Options array where:
     *                              'format' is the format of the string
     *                                  Ex: VALIDATE_NUM . VALIDATE_ALPHA (see constants)
     *                              'min_length' minimum length
     *                              'max_length' maximum length
     */
    function string($string, $options)
    {
        $format = null;
        $min_length = $max_length = 0;
        if (is_array($options)){
            extract($options);
        }
        if ($format && !preg_match("|^[$format]*\$|s", $string)) {
            return false;
        }
        if ($min_length && strlen($string) < $min_length) {
            return false;
        }
        if ($max_length && strlen($string) > $max_length) {
            return false;
        }
        return true;
    }

    /**
     * Validate an URI (RFC2396)
     *
     * @param string    $url        URI to validate
     * @param array     $options    Options used by the validation method.
     *                              key => type
     *                              'domain_check' => boolean
     *                                  Whether to check the DNS entry or not
     *                              'allowed_schemes' => array, list of protocols
     *                                  List of allowed schemes ('http',
     *                                  'ssh+svn', 'mms')
     */
    function uri($url, $options = null)
    {
        $domain_check = false;
        $allowed_schemes = null;
        if (is_array($options)) {
            extract($options);
        }
        if (preg_match(
            '!^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?!',
            $url,$matches)
        ) {
            $scheme = $matches[2];
            $authority = $matches[4];
            if ( is_array($allowed_schemes) &&
                !in_array($scheme,$allowed_schemes)
            ) {
                return false;
            }
            if ($domain_check && function_exists('checkdnsrr')) {
                if (!checkdnsrr($authority, 'A')) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Validate a number according to Luhn check algorithm
     *
     * This function checks given number according Luhn check
     * algorithm. It is published on several places, also here:
     *
     *      http://www.webopedia.com/TERM/L/Luhn_formula.html
     *      http://www.merriampark.com/anatomycc.htm
     *      http://hysteria.sk/prielom/prielom-12.html#3 (Slovak language)
     *      http://www.speech.cs.cmu.edu/~sburke/pub/luhn_lib.html (Perl lib)
     *
     * @param  string  $number number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Ondrej Jombik <nepto@pobox.sk>
     */
    function creditCard($creditCard)
    {
        $creditCard = preg_replace('/[^0-9]/','',$creditCard);
        if (empty($creditCard) || ($len_number = strlen($creditCard)) <= 0) {
            return false;
        }
        $sum = 0;
        for ($k = $len_number % 2; $k < $len_number; $k += 2) {
            if ((intval($creditCard{$k}) * 2) > 9) {
                $sum += (intval($creditCard{$k}) * 2) - 9;
            } else {
                $sum += intval($creditCard{$k}) * 2;
            }
        }
        for ($k = ($len_number % 2) ^ 1; $k < $len_number; $k += 2) {
            $sum += intval($creditCard{$k});
        }
        return $sum % 10 ? false : true;
    }

    /**
     * Validate date and times. Note that this method need the Date_Calc class
     *
     * @param string    $date   Date to validate
     * @param array     $options array options where :
     *                          'format' The format of the date (%d-%m-%Y)
     *                          'min' The date has to be greater
     *                                than this array($day, $month, $year)
     *                          'max' The date has to be smaller than
     *                                this array($day, $month, $year)
     *
     * @return bool
     */
    function date($date, $options)
    {
        $max = $min = false;
        $format = '';
        if (is_array($options)){
            extract($options);
        }
        $date_len   = strlen($format);
        for ($i = 0; $i < strlen($format); $i++) {
            $c = $format{$i};
            if ($c == '%') {
                $next = $format{$i + 1};
                switch ($next) {
                    case 'j':
                    case 'd':
                        if ($next == 'j') {
                            $day = (int)Validate::_substr($date, 1, 2);
                        } else {
                            $day = (int)Validate::_substr($date, 2);
                        }
                        if ($day < 1 || $day > 31) {
                            return false;
                        }
                        break;
                    case 'm':
                    case 'n':
                        if ($next == 'm') {
                            $month = (int)Validate::_substr($date, 2);
                        } else {
                            $month = (int)Validate::_substr($date, 1, 2);
                        }
                        if ($month < 1 || $month > 12) {
                            return false;
                        }
                        break;
                    case 'Y':
                    case 'y':
                        if ($next == 'Y') {
                            $year = Validate::_substr($date, 4);
                            $year = (int)$year?$year:'';
                        } else {
                            $year = (int)(substr(date('Y'), 0, 2) .
                                          Validate::_substr($date, 2));
                        }
                        if (strlen($year) != 4 || $year < 0 || $year > 9999) {
                            return false;
                        }
                        break;
                    case 'g':
                    case 'h':
                        if ($next == 'g') {
                            $hour = Validate::_substr($date, 1, 2);
                        } else {
                            $hour = Validate::_substr($date, 2);
                        }
                        if ($hour < 0 || $hour > 12) {
                            return false;
                        }
                        break;
                    case 'G':
                    case 'H':
                        if ($next == 'G') {
                            $hour = Validate::_substr($date, 1, 2);
                        } else {
                            $hour = Validate::_substr($date, 2);
                        }
                        if ($hour < 0 || $hour > 24) {
                            return false;
                        }
                        break;
                    case 's':
                    case 'i':
                        $t = Validate::_substr($date, 2);
                        if ($t < 0 || $t > 59) {
                            return false;
                        }
                        break;
                    default:
                        trigger_error("Not supported char `$next' after % in offset " . ($i+2), E_USER_WARNING);
                }
                $i++;
            } else {
                //literal
                if (Validate::_substr($date, 1) != $c) {
                    return false;
                }
            }
        }
        // there is remaing data, we don't want it
        if (strlen($date)) {
            return false;
        }
        if (isset($day) && isset($month) && isset($year)) {
            if (!checkdate($month, $day, $year)) {
                return false;
            }
            if ($min || $max) {
                include_once 'Date/Calc.php';
                if ($min &&
                    (Date_Calc::compareDates($day, $month, $year,
                                             $min[0], $min[1], $min[2]) < 0))
                {
                    return false;
                }
                if ($max &&
                    (Date_Calc::compareDates($day, $month, $year,
                                             $max[0], $max[1], $max[2]) > 0))
                {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Validate a ISBN number
     *
     * This function checks given number according
     *
     * @param  string  $isbn number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Damien Seguy <dams@nexen.net>
     */
    function isbn($isbn)
    {
        if (preg_match("/[^0-9 IXSBN-]/", $isbn)) {
            return false;
        }

        if (!ereg("^ISBN", $isbn)){
            return false;
        }

        $isbn = ereg_replace("-", "", $isbn);
        $isbn = ereg_replace(" ", "", $isbn);
        $isbn = eregi_replace("ISBN", "", $isbn);
        if (strlen($isbn) != 10) {
            return false;
        }
        if (preg_match("/[^0-9]{9}[^0-9X]/", $isbn)){
            return false;
        }

        $t = 0;
        for($i=0; $i< strlen($isbn)-1; $i++){
            $t += $isbn[$i]*(10-$i);
        }
        $f = $isbn[9];
        if ($f == "X") {
            $t += 10;
        } else {
            $t += $f;
        }
        if ($t % 11) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * Validate an ISSN (International Standard Serial Number)
     *
     * This function checks given ISSN number
     * ISSN identifies periodical publications:
     * http://www.issn.org
     *
     * @param  string  $issn number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function issn($issn)
    {
        static $weights_issn = array(8,7,6,5,4,3,2);

        $issn = strtoupper($issn);
        $issn = eregi_replace("ISSN", "", $issn);
        $issn = str_replace(array('-','/',' ',"\t","\n"), '', $issn);
        $issn_num = eregi_replace("X", "0", $issn);

        // check if this is an 8-digit number
        if (!is_numeric($issn_num) || strlen($issn) != 8) {
            return false;
        }

        return Validate::_check_control_number($issn, $weights_issn, 11, 11);
    }

    /**
     * Validate a ISMN (International Standard Music Number)
     *
     * This function checks given ISMN number (ISO Standard 10957)
     * ISMN identifies all printed music publications from all over the world
     * whether available for sale, hire or gratis--whether a part, a score,
     * or an element in a multi-media kit:
     * http://www.ismn-international.org/
     *
     * @param  string  $ismn ISMN number
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function ismn($ismn)
    {
        static $weights_ismn = array(3,1,3,1,3,1,3,1,3);

        $ismn = eregi_replace("ISMN", "", $ismn);
        $ismn = eregi_replace("M", "3", $ismn); // change first M to 3
        $ismn = str_replace(array('-','/',' ',"\t","\n"), '', $ismn);

        // check if this is a 10-digit number
        if (!is_numeric($ismn) || strlen($ismn) != 10) {
            return false;
        }

        return Validate::_check_control_number($ismn, $weights_ismn, 10, 10);
    }


    /**
     * Validate a EAN/UCC-8 number
     *
     * This function checks given EAN8 number
     * used to identify trade items and special applications.
     * http://www.ean-ucc.org/
     * http://www.uc-council.org/checkdig.htm
     *
     * @param  string  $ean number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function ean8($ean)
    {
        static $weights_ean8 = array(3,1,3,1,3,1,3);

        $ean = str_replace(array('-','/',' ',"\t","\n"), '', $ean);

        // check if this is a 8-digit number
        if (!is_numeric($ean) || strlen($ean) != 8) {
            return false;
        }

        return Validate::_check_control_number($ean, $weights_ean8, 10, 10);
    }

    /**
     * Validate a EAN/UCC-13 number
     *
     * This function checks given EAN/UCC-13 number used to identify
     * trade items, locations, and special applications (e.g., coupons)
     * http://www.ean-ucc.org/
     * http://www.uc-council.org/checkdig.htm
     *
     * @param  string  $ean number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function ean13($ean)
    {
        static $weights_ean13 = array(1,3,1,3,1,3,1,3,1,3,1,3);

        $ean = str_replace(array('-','/',' ',"\t","\n"), '', $ean);

        // check if this is a 13-digit number
        if (!is_numeric($ean) || strlen($ean) != 13) {
            return false;
        }

        return Validate::_check_control_number($ean, $weights_ean13, 10, 10);
    }

    /**
     * Validate a EAN/UCC-14 number
     *
     * This function checks given EAN/UCC-14 number
     * used to identify trade items.
     * http://www.ean-ucc.org/
     * http://www.uc-council.org/checkdig.htm
     *
     * @param  string  $ean number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function ean14($ean)
    {
        static $weights_ean14 = array(3,1,3,1,3,1,3,1,3,1,3,1,3);

        $ean = str_replace(array('-','/',' ',"\t","\n"), '', $ean);

        // check if this is a 14-digit number
        if (!is_numeric($ean) || strlen($ean) != 14) {
            return false;
        }

        return Validate::_check_control_number($ean, $weights_ean14, 10, 10);
    }

    /**
     * Validate a UCC-12 (U.P.C.) ID number
     *
     * This function checks given UCC-12 number used to identify
     * trade items, locations, and special applications (e.g., * coupons)
     * http://www.ean-ucc.org/
     * http://www.uc-council.org/checkdig.htm
     *
     * @param  string  $ucc number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function ucc12($ucc)
    {
        static $weights_ucc12 = array(3,1,3,1,3,1,3,1,3,1,3);

        $ucc = str_replace(array('-','/',' ',"\t","\n"), '', $ucc);

        // check if this is a 12-digit number
        if (!is_numeric($ucc) || strlen($ucc) != 12) {
            return false;
        }

        return Validate::_check_control_number($ucc, $weights_ucc12, 10, 10);
    }

    /**
     * Validate a SSCC (Serial Shipping Container Code)
     *
     * This function checks given SSCC number
     * used to identify logistic units.
     * http://www.ean-ucc.org/
     * http://www.uc-council.org/checkdig.htm
     *
     * @param  string  $sscc number (only numeric chars will be considered)
     * @return bool    true if number is valid, otherwise false
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function sscc($sscc)
    {
        static $weights_sscc = array(3,1,3,1,3,1,3,1,3,1,3,1,3,1,3,1,3);

        $sscc = str_replace(array('-','/',' ',"\t","\n"), '', $sscc);

        // check if this is a 18-digit number
        if (!is_numeric($sscc) || strlen($sscc) != 18) {
            return false;
        }

        return Validate::_check_control_number($sscc, $weights_sscc, 10, 10);
    }

    function _substr(&$date, $num, $opt = false)
    {
        if ($opt && strlen($date) >= $opt && preg_match('/^[0-9]{'.$opt.'}/', $date, $m)) {
            $ret = $m[0];
        } else {
            $ret = substr($date, 0, $num);
        }
        $date = substr($date, strlen($ret));
        return $ret;
    }

    function _modf($val, $div) {
        if( function_exists('bcmod') ){
            return bcmod($val,$div);
        } else if (function_exists('fmod')) {
            return fmod($val,$div);
        }
        $r = $a / $b;
        $i = intval($r);
        return intval(($r - $i) * $b);
    }

    /**
     * Calculates sum of product of number digits with weights
     *
     * @param string $number number string
     * @param array $weights reference to array of weights
     * @returns int returns product of number digits with weights
     */
    function _mult_weights($number, &$weights) {
        if (!is_array($weights))
            return -1;

        $sum = 0;

        $count = min(count($weights), strlen($number));
        if ($count == 0) // empty string or weights array
            return -1;
        for ($i=0; $i<$count; ++$i) {
            $sum += intval(substr($number,$i,1)) * $weights[$i];
        }

        return $sum;
    }

    /**
     * Calculates control digit for a given number
     *
     * @param string $number number string
     * @param array $weights reference to array of weights
     * @param int $modulo (optionsl) number
     * @param int $subtract (optional) number
     * @param bool $allow_high (optional) true if function can return number higher than 10
     * @returns int -1 calculated control number is returned
     */
    function _get_control_number($number, &$weights, $modulo = 10, $subtract = 0, $allow_high = false) {
        // calc sum
        $sum = Validate::_mult_weights($number, $weights);
        if ($sum == -1)
            return -1;

        $mod = Validate::_modf($sum, $modulo);  /* calculate control digit  */

        if ($subtract > $mod)
            $mod = $subtract - $mod;

        if ($allow_high === false)
          $mod %= 10;           /* change 10 to zero        */
        return $mod;
    }

    /**
     * Validates a number
     *
     * @param string $number number to validate
     * @param array $weights reference to array of weights
     * @param int $modulo (optionsl) number
     * @param int $subtract (optional) numbier
     * @returns bool
     **/
    function _check_control_number($number, &$weights, $modulo = 10, $subtract = 0) {
        if (strlen($number) < count($weights))
            return false;

        $target_digit  = substr($number, count($weights), 1);
        $control_digit = Validate::_get_control_number($number, $weights, $modulo, $subtract, $target_digit === 'X');

        if ($control_digit == -1)
            return false;

        if ($target_digit === 'X' && $control_digit == 10)
            return true;

        if ($control_digit != $target_digit)
            return false;

        return true;
    }

    /**
    * Bulk data validation for data introduced in the form of an
    * assoc array in the form $var_name => $value.
    *
    * @param  array   $data     Ex: array('name'=>'toto','email'='toto@thing.info');
    * @param  array   $val_type Contains the validation type and all parameters used in.
    *                           'val_type' is not optional
    *                           others validations properties must have the same name as the function
    *                           parameters.
    *                           Ex: array('toto'=>array('type'=>'string','format'='toto@thing.info','min_length'=>5));
    * @param  boolean $remove if set, the elements not listed in data will be removed
    *
    * @return array   value name => true|false    the value name comes from the data key
    */
    function multiple(&$data, &$val_type, $remove = false)
    {
        $keys = array_keys($data);
        foreach ($keys as $var_name) {
            if (!isset($val_type[$var_name])) {
                if ($remove) {
                    unset($data[$var_name]);
                }
                continue;
            }
            $opt = $val_type[$var_name];
            $methods = get_class_methods('Validate');
            $val2check = $data[$var_name];
            // core validation method
            if (in_array(strtolower($opt['type']), $methods)) {
                //$opt[$opt['type']] = $data[$var_name];
                $method = $opt['type'];
                $opt = array_slice($opt,1);

                if (sizeof($opt) == 1){
                    $opt = array_pop($opt);
                }
                $valid[$var_name] = call_user_func(array('Validate', $method), $val2check,$opt);

            /**
             * external validation method in the form:
             * "<class name><underscore><method name>"
             * Ex: us_ssn will include class Validate/US.php and call method ssn()
             */
            } elseif (strpos($opt['type'],'_') !== false) {
                list($class, $method) = explode('_', $opt['type'], 2);
                @include_once("Validate/$class.php");
                if (!class_exists("Validate_$class") ||
                    !in_array($method, get_class_methods("Validate_$class"))) {
                    trigger_error("Invalid validation type Validate_$class::$method", E_USER_WARNING);
                    continue;
                }
                $opt = array_slice($opt,1);
                if (sizeof($opt) == 1){
                    $opt = array_pop($opt);
                }
                $valid[$var_name] = call_user_func(array("Validate_$class", $method), $data[$var_name],$opt);
            } else {
                trigger_error("Invalid validation type {$opt['type']}", E_USER_WARNING);
            }
        }
        return $valid;
    }
}
?>
