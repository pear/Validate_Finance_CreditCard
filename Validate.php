<?php

die('Unfinished, untested, unworking. Don\'t use it');

define('VAL_NUM',          '0-9');
define('VAL_SPACE',        '\s');
define('VAL_ALPHA_LOWER',  'a-z');
define('VAL_ALPHA_UPPER',  'A-Z');
define('VAL_ALPHA',        VAL_ALPHA_LOWER . VAL_ALPHA_UPPER);
define('VAL_EALPHA_LOWER', VAL_ALPHA_LOWER . 'ביםףתאטלעשהכןצüגךמפûסח');
define('VAL_EALPHA_UPPER', VAL_ALPHA_UPPER . 'ֱֹֽ׃ÚְָּׂÙִֻֿײÜֲÊ־װÛַׁ');
define('VAL_EALPHA',       VAL_EALPHA_LOWER . VAL_EALPHA_UPPER);
define('VAL_PUNCTUATION',  VAL_SPACE . ',;:"\'\?!\(\)');
define('VAL_NAME',         VAL_EALPHA . VAL_SPACE . "'");
define('VAL_STREET',       VAL_NAME . "/\\÷×");

class Validate
{
    /**
    * @param mixed $decimal The decimal char or false when decimal not allowed
    */
    function number($number, $decimal = false)
    {
        $decimal = $decimal ? $decimal . '[0-9]+' : '';
        return ereg("^[0-9]+$decimal\$", $number);
    }

    function email($email, $check_domain = false)
    {
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
        if ($format && !preg_match("|^[$format]*\$|s", $string)) {
            return false;
        }
        if ($min_lenght && strlen($string) <= $min_lenght) {
            return false;
        }
        if ($max_lenght && strlen($string) >= $max_lenght) {
            return false;
        }
        return true;
    }

    function date($date, $format, $min_range = false, $max_range = false)
    {
        if (!include_once 'Date/Date.php') {
            trigger_error(' no date class found ..', E_USER_ERROR);
            return false;
        }
    }

    function multiple($data, $options)
    {

    }
}
?>