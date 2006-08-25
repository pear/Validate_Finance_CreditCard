<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2005 Pierre-Alain Joye,Tomas V.V.Cox              |
// +----------------------------------------------------------------------+
// | This source file is subject to the New BSD license, That is bundled  |
// | with this package in the file LICENSE, and is available through      |
// | the world-wide-web at                                                |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// | If you did not receive a copy of the new BSDlicense and are unable   |
// | to obtain it through the world-wide-web, please send a note to       |
// | pajoye@php.net so we can mail you a copy immediately.                |
// +----------------------------------------------------------------------+
// | Author: Tomas V.V.Cox  <cox@idecnet.com>                             |
// |         Pierre-Alain Joye <pajoye@php.net>                           |
// |         Byron Adams <byron.adams54@gmail.com>
// +----------------------------------------------------------------------+
//
/**
 * Specific validation methods for data used in Spain
 *
 * @category   Validate
 * @package    Validate_ES
 * @author     Tomas V.V.Cox <cox@idecnet.com>
 * @author     Byron Adams <byron.adams54@gmail.com>
 * @copyright  1997-2005 Pierre-Alain Joye,Tomas V.V.Cox
 * @copyright  (c) 2006 Byron Adams
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/Validate_ES
 */

/**
* Requires base class Validate
*/
require_once 'Validate.php';

/**
 * Data validation class for Spain
 *
 * This class provides methods to validate:
 *  - Spanish DNI number ("El documento de la identificación nacional")
 *
 * @category   Validate
 * @package    Validate_ES
 * @author     Tomas V.V.Cox <cox@idecnet.com>
 * @author     Byron Adams <byron.adams54@gmail.com>
 * @copyright  1997-2005 Pierre-Alain Joye, Tomas V.V.Cox
 * @copyright  (c) 2006 Byron Adams
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Validate_ES
 */
class Validate_ES
{
    /**
    * Validate Spanish DNI number ("El documento de la identificación nacional")
    *
    * In Spain, all Spanish citizens are issued with a DNI 
    * the numbers are used as identification for almost all purposes.
    * 
    * @param     string    $dni El Documento Nacional de Indentidad a chequear
    * @return    bool      returns true on success false otherwise
    * @author    Tomas V.V.Cox <cox@idecnet.com>
    * @author    Byron Adams <byron.adams54@gmail.com>
    * @link      http://es.wikipedia.org/wiki/Algoritmo_para_obtener_la_letra_del_NIF
    * @link      http://nationalidentificationnumber.quickseek.com/#Spain
    */
    function dni($dni)
    {
        $dni = str_replace("-","",trim($dni));
        $letters = 'TRWAGMYFPDXBNJZSQVHLCKET';   
       
        $start = (int)(strtoupper($dni{0}) == "X");
        
        $number = substr($dni,$start,-1);
        $letter = strtoupper(substr($dni, -1));
        
        if(!ctype_digit($number) ||
           !ctype_alpha($letter)) {
           return false;
        }
        
        return ($letter == $letters{$number % 23});
    }
}
?>