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
// +----------------------------------------------------------------------+
//
// $Id$
//
// Specific validation methods for data used in Spain
//

require_once('Validate.php');

class Validate_ES
{
    /**
    * Valida un DNI Español (el dni tiene que ser de la forma 11111111X)
    *
    * @param string $dni El Documento Nacional de Indentidad a chequear
    * @return bool
    */
    function dni($dni)
    {
        $letra  = substr($dni, -1);
        $number = substr($dni, 0, -1);
        if (!Validate::string($number, VALIDATE_NUM, 8, 8)) {
            return false;
        }
        if (!Validate::string($letra, VALIDATE_ALPHA)) {
            return false;
        }
        // El resto entero de la division del numero del dni/23 +1
        // es la posicion de la letra en la cadena $string
        $string = 'TRWAGMYFPDXBNJZSQVHLCKET';
        // ver la letra de un numero
        if ($letra == $string{$number % 23}) {
            return true;
        }
        return false;
    }
}
?>