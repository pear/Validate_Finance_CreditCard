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
// | Authors: Piotr Klaban <makler@man.torun.pl>                          |
// +----------------------------------------------------------------------+
//
// $Id$
//
// Specific validation methods for data used in Poland
//

class Validate_PL
{
    /**
     * Validates NIP (Polish tax identification number)
     *
     * Sprawdza NIP (Numer Identyfikacji Podatkowej)
     * http://chemeng.p.lodz.pl/zylla/ut/nip-rego.html
     * http://www.republika.pl/stepa/cyfra2.htm
     *
     * @param string $nip 9-digit number to validate
     * @returns bool
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function nip($nip)
    {
        static $weights_nip = array(6,5,7,2,3,4,5,6,7);

        // remove any dashes, spaces, returns, tabs or slashes
        $nip = str_replace(array('-','/',' ',"\t","\n"), '', $nip);

        // check if this is a 10-digit number
        if (!is_numeric($nip) || strlen($nip) != 10) {
            return false;
        }

        // check control sum
        return Validate::_check_control_number($nip, $weights_nip, 11);
    }

    /**
     * Validates bank number (Polish banks)
     *
     * @param string $number 8-digit number to validate
     * @returns bool
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function bank_branch($number)
    {
        static $weights_bank_branch = array(7,1,3,9,7,11,3);

        // remove any dashes, spaces, returns, tabs or slashes
        $number = str_replace(array('-','/',' ',"\t","\n"), '', $number);

        // check if this is a 8-digit number
        if (!is_numeric($number) || strlen($number) != 8) {
            return false;
        }

        // check control sum
        return Validate::_check_control_number($number, $weights_bank_branch, 10);
    }

    /**
     * Validates PESEL (Polish human identification number)
     *
     * Sprawdza PESEL (Powszechny Elektroniczny System Ewidencji Ludno¶ci)
     * http://www.mswia.gov.pl/crp_pesel.html
     * NOTE: some people can have the same PESEL, and some can have
     * PESEL with wrong numbers in place of birth date.
     *
     * @param string $pesel 11-digit number to validate
     * @param array  $birth reference to array - returns birth date and sex
     *               (either 'female' or 'male' string) extracted from pesel
     * @returns bool
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function pesel($pesel, &$birth)
    {
        static $weights_pesel = array(1,3,7,9,1,3,7,9,1,3);

        $birth = array(false,false);

        // remove any dashes, spaces, returns, tabs or slashes
        $pesel = str_replace(array('-','/',' ',"\t","\n"), '', $pesel);

        // check if this is a 11-digit number
        if (!is_numeric($pesel) || strlen($pesel) != 11) {
            return false;
        }

        if (Validate::_check_control_number($pesel, $weights_pesel, 10, 10) === false)
            return false;

        // now extract birth date from PESEL number
        $vy = substr($pesel,0,2);
        $vm = substr($pesel,2,2);
        $vd = substr($pesel,4,2);

        // decode century
        if ($vm < 20)
            $vy += 1900;
        elseif ($vm < 40)
            $vy += 2000;
        elseif ($vm < 60)
            $vy += 2100;
        elseif ($vm < 80)
            $vy += 2200;
        else
            $vy += 1800;
        $vm %= 20;
        $birth[0] = "$vy-$vm-$vd";

        // decode gender
        $gender = substr($pesel,9,1) % 2;
        $birth[1] = ($gender % 2 == 0) ? 'female' : 'male';

        return true;
    }

    /**
     * Validates REGON (Polish statistical national economy register)
     *
     * Sprawdza REGON (Rejestr Gospodarki Narodowej)
     * http://chemeng.p.lodz.pl/zylla/ut/nip-rego.html
     *
     * @param string $nip 9- or 14- digit number to validate
     * @returns bool
     * @author Piotr Klaban <makler@man.torun.pl>
     */
    function regon($regon)
    {
        static $weights_regon = array(8,9,2,3,4,5,6,7);
        static $weights_regon_local = array(2,4,8,5,0,9,7,3,6,1,2,4,8);

        // remove any dashes, spaces, returns, tabs or slashes
        $regon = str_replace(array('-','/',' ',"\t","\n"), '', $regon);

        // check if this is a 9- or 14-digit number
        if (!is_numeric($regon) || (strlen($regon) != 9 && strlen($regon) != 14)) {
            return false;
        }

        // first check first 9 digits
        if (Validate::_check_control_number($regon, $weights_regon, 11) === false)
          return false;

        // check wide number if there are 14 digits
        if (strlen($regon) == 14)
        {
            // check 14 digits
            return Validate::_check_control_number($regon, $weights_regon_local, 11);
        }

        return true;
    }
}
?>
