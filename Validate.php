<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Tomas V.V.Cox <cox@idecnet.com>                             |
// |          Pierre-Alain Joye <pajoye@phpindex.com>                     |
// |                                                                      |
// +----------------------------------------------------------------------+
//
// $Id$
//
// Methods for common data validations
//

/**
Experimental
*/

define('VAL_NUM',          '0-9');
define('VAL_SPACE',        '\s');
define('VAL_ALPHA_LOWER',  'a-z');
define('VAL_ALPHA_UPPER',  'A-Z');
define('VAL_ALPHA',        VAL_ALPHA_LOWER . VAL_ALPHA_UPPER);
define('VAL_EALPHA_LOWER', VAL_ALPHA_LOWER . 'áéíóúàèìòùäëïöüâêîôûñç');
define('VAL_EALPHA_UPPER', VAL_ALPHA_UPPER . 'ÁÉÍÓÚÀÈÌÒÙÄËÏÖÜÂÊÎÔÛÑÇ');
define('VAL_EALPHA',       VAL_EALPHA_LOWER . VAL_EALPHA_UPPER);
define('VAL_PUNCTUATION',  VAL_SPACE . ',;:"\'\?!\(\)');
define('VAL_NAME',         VAL_EALPHA . VAL_SPACE . "'");
define('VAL_STREET',       VAL_NAME . "/\\ºª");

class Validate
{

    function Validate() {
    }

    /**
    * @param mixed $decimal The decimal char or false when decimal not allowed
    */
    function number($number, $decimal = null, $dec_prec = null, $min = null, $max = null)
    {
        if ( is_array( $number ) )
            extract($number);

        $dec_prec   = $dec_prec ? "{1,$dec_prec}" : '+';
        $dec_regex  = $decimal  ? "[$decimal][0-9]$dec_prec" : '';
        if (!preg_match("|^[-+]?\s*[0-9]+($dec_regex)?\$|", $number)) {
            return false;
        }
        if ($decimal != '.') {
            $number = strtr($number, $decimal, '.');
        }
        $number = (float)$number;
        if ($min !== null && $min > $number) {
            return false;
        }
        if ($max !== null && $max < $number) {
            return false;
        }
        return true;
    }

    function email($email, $check_domain = false)
    {
        if ( is_array( $email ) )
            extract($email);

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
    * @param string $format Ex: VAL_NUM . VAL_ALPHA
    */
    function string($string, $format = null, $min_lenght = 0, $max_lenght = 0)
    {
        if ( is_array( $string ) )
            extract($string);

        if ($format && !preg_match("|^[$format]*\$|s", $string)) {
            return false;
        }
        if ($min_lenght && strlen($string) < $min_lenght) {
            return false;
        }
        if ($max_lenght && strlen($string) > $max_lenght) {
            return false;
        }
        return true;
    }

    // move outside
    /*
    function date($date, $format, $min_range = false, $max_range = false)
    {
        if (!include_once 'Date/Date.php') {
            trigger_error(' no date class found ..', E_USER_ERROR);
            return false;
        }
    }
    */

    function url($url, $domain_check = false)
    {
        if ( is_array( $url ) )
            extract($url);

        $purl = parse_url($url);
        if (preg_match('|^http$|i', @$purl['scheme']) && !empty($purl['host'])) {
            if ($domain_check && function_exists('checkdnsrr')) {
                if (checkdnsrr($purl['host'], 'A')) {
                    return true;
                } else {
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
     * @param  string $number number
     * @return bool           true if number is valid, otherwise false
     * @author Ondrej Jombik <nepto@pobox.sk>
     */
    function creditCard($number)
    {
        if (empty($number) || ($len_number = strlen($number)) <= 0) {
            return false;
        }
        $sum = 0;
        for ($k = $len_number % 2; $k < $len_number; $k += 2) {
            if ((intval($number[$k]) * 2) > 9) {
                $sum += (intval($number[$k]) * 2) - 9;
            } else {
                $sum += intval($number[$k]) * 2;
            }
        }
        for ($k = ($len_number % 2) ^ 1; $k < $len_number; $k += 2) {
            $sum += intval($number[$k]);
        }
        return $sum % 10 ? false : true;
    }

    /**
    * Validate date and times. Note that this method need the Date_Calc class
    *
    * @param string $date Date to validate
    * @param string $format The format of the date (%d-%m-%Y)
    * @param array  $min The date has to be greater than this array($day, $month, $year)
    * @param array  $max The date has to be smaller than this array($day, $month, $year)
    *
    * @return bool
    */
    function date($date, $format, $min = array(), $max = array())
    {
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
                            $month = (int)Validate::_substr($date, 1, 2); break;
                        }
                        if ($month < 1 || $month > 12) {
                            return false;
                        }
                        break;
                    case 'Y':
                    case 'y':
                        if ($next == 'Y') {
                            $year = (int)Validate::_substr($date, 4); break;
                        } else {
                            $year = (int)(substr(date('Y'), 2) .
                                          Validate::_substr($date, 2));
                        }
                        if ($year < 0 || $year > 9999) {
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
                        if ($t < 0 || $t > 60) {
                            return false;
                        }
                        break;
                    default:
                        trigger_error("Not supported char `$next' after %", E_USER_WARNING);
                }
                $i++;
            } else {
                //literal
                if (Validate::_substr($date, 1) != $c) {
                    return false;
                }
            }
        }
        if (isset($day) && isset($month) && isset($year)) {
            include_once 'Date/Calc.php';
            if (!Date_Calc::isValidDate($day, $month, $year)) {
                return false;
            }
            if ($min &&
                (Date_Calc::compareDates($day, $month, $year,
                                         $min[0], $min[1], $min[2]) > 0))
            {
                return false;
            }
            if ($max &&
                (Date_Calc::compareDates($day, $month, $year,
                                         $max[0], $max[1], $max[2]) < 0))
            {
                return false;
            }
        }
        return true;
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

    /*
        To Do :
        External calls based on commented Date methods

        @param  array   $data Ex: array('name'=>'toto','email'='toto@thing.info');
        @param  array   $opt   Contains the validation type and all parameters used in.
                            'type' is not optional
                            others validations properties must have the same name as the function
                            parameters.
                            Ex: array('toto'=>array('type'=>'string','format'='toto@thing.info','min_lenght'=>5));
        @param  boolean $remove if set, the invalid elements in data will be removed ( Not yet implemented)

        @return array   value name => true|false    the value name comes from the data key
    */
    function multiple( &$data, &$val_type, $remove = false  ) {
        $core_methods = array('number','string','email','url');

        if( !is_null($data) && !is_null($val_type)  ){

            $keys   = array_keys( $data );
            foreach( $keys as $var_name ) {
                if (isset( $val_type[$var_name] ))
                    $opt    = $val_type[$var_name];

                if ( isset( $opt['type'] ) && $opt['type']!="" )
                    if ( in_array( $opt['type'], $core_methods ) ){
                        $opt[$opt['type']] = $data[$var_name];
                        $valid[$var_name] = Validate::$opt['type']($opt);
                    } else {
                        // External call
                    }
            }
            return $valid;
        } else {
            return null;
        }
    }
}
?>
