<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Specific validation methods for data used in the UK
 *
 * PHP Versions 4 and 5
 *
 * This source file is subject to the New BSD license, That is bundled
 * with this package in the file LICENSE, and is available through
 * the world-wide-web at
 * http://www.opensource.org/licenses/bsd-license.php
 * If you did not receive a copy of the new BSDlicense and are unable
 * to obtain it through the world-wide-web, please send a note to
 * pajoye@php.net so we can mail you a copy immediately.
 *
 * @category  Validate
 * @package   Validate_UK
 * @author    Michael Dransfield <mikeNO@SPAMblueroot.net>
 * @author    Ian P. Christian <pookey@pookey.co.uk>
 * @author    Tomas V.V.Cox <cox@idecnet.com>
 * @author    Pierre-Alain Joye <pajoye@php.net>
 * @copyright 1997-2005 Michael Dransfield, Ian P. Christian, Pierre-Alain Joye
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_UK
 */


/**
 * validateVehiclePre1932
 *
 * input must be uppercased and spaces and dashes removed
 *
 * @param string $input car reg
 *
 * @access public
 * @return bool
 */
function validateVehiclePre1932($input)
{
    if (!preg_match('/^[A-Z]{1,2}\d{1,4}$/', $input, $m)) {
        // invalidly formed
        return false;
    }
}

/**
 * validateVehicle1932
 *
 * @param string $input car reg
 *
 * @access public
 * @return bool
 */
function validateVehicle1932($input)
{
    if (!preg_match('/^[A-Z]{3}\d{1,3}$/', $input, $m)) {
        // invalidly formed
        return false;
    }
}

/**
 * validateVehicle1950
 *
 * @param string $input car reg
 *
 * @access public
 * @return bool
 */
function validateVehicle1950($input)
{
    if (!preg_match('/^\d{1,3}[A-Z]{3}\d{1,4}[A-Z]{1,3}$/', $input, $m)) {
        // invalidly formed
        return false;
    }
}

/**
 * validateVehicle1963
 *
 * @param string $input car reg
 *
 * @access public
 * @return bool
 */
function validateVehicle1963($input)
{
    if (!preg_match('/^([A-Z]{3})\d{1,3}([A-Z]?)$/', $input, $m)) {
        // invalidly formed
        return false;
    }
}

/**
 * validateVehicle1982
 *
 * @param string $input car reg
 *
 * @access public
 * @return bool
 */
function validateVehicle1982($input)
{
    if (!preg_match('/^([A-Z])\d{1,3}[A-Z]{3}$/', $input, $m)) {
        // invalidly formed
        return false;
    }
    $year = ord($m[1]) - 65;
    if ($year > 15) {
        // two letters per year
        $year -= floor(($year - 15) / 2);
    }
    $year += 1984;
    // is there a region list anywhere?
    return array(
        'year' => $year
    );
}

/**
 * validateVehicle2001
 *
 * @param string $input car reg
 *
 * @access public
 * @return bool
 */
function validateVehicle2001($input)
{
    if (!preg_match('/^([A-Z]{2})(\d{2})([A-Z]{3})$/', $input, $m)) {
        // invalidly formed
        return false;
    }
    global $regions2001;
    if (!isset($regions2001[$m[1][0]])) {
        // region can't be found
        return false;
    }
    $region = $regions2001[$m[1]];
    $dvla   = false;
    for ($i = count($region['dvla']); $i--;) {
        if (strpos($region['dvla'][$i], $m[1][1]) !== false) {
            $dvla = $region['dvla'][$i];
            break;
        }
    }
    if ($dvla === false) {
        // dvla office can't be found
        return false;
    }
    $cur_month = date('n');
    if ($m[2] > 50) {
        $m[2] -= 50;
    }
    if ($cur_month < 3) {
        $m[2]++;
    }
    if ($m[2] < 1) {
        // invalid year
        return false;
    }
    $year = 2000 + $m[2];
    if (strpbrk($m[3], 'IQ') !== false) {
        // invalid suffix
        return false;
    }
    return array(
        'region' => $region['region'],
        'year'   => 2000 + $m[2],
        'dvla'   => $dvla
    );
}

// source: http://en.wikipedia.org/wiki/British_car_number_plate_identifiers
$regions2001 = array(
    'A' => array(
            'region' => 'East Anglia',
            'dvla'  => array(
                'ABCDEFGHJKLMN' => 'Peterborough',
                'OPRSTU'        => 'Norwich',
                'VWXY'          => 'Ipswich'
            )
    ),
    'B' => array(
            'region' => 'Birmingham',
            'dvla' => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Birmingham'
            )
    ),
    'C' => array(
            'region' => 'Cymru',
            'dvla'  => array(
                'ABCDEFGHJKLMNO' => 'Cardiff',
                'PRSTUV'         => 'Swansea',
                'WXY'            => 'Bangor'
            )
    ),
    'D' => array(
            'region' => 'Deeside etc.',
            'dvla' => array(
                'ABCDEFGHJK'    => 'Chester',
                'LMNOPRSTUVWXY' => 'Shrewsbury'
            )
    ),
    'E' => array(
            'region' => 'Essex and Hertfordshire',
            'dvla' => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Chelmsford'
            )
    ),
    'F' => array(
            'region' => 'Forest and Ferns',
            'dvla'  => array(
                'ABCDEFGHJKLMNOP' => 'Nottingham',
                'RSTUVWXY'        => 'Lincoln'
            )
    ),
    'G' => array(
            'region' => 'Garden of England',
            'dvla'  => array(
                'ABCDEFGHJKLMNO' => 'Maidstone',
                'PRSTUVWXY'      => 'Brighton'
            )
    ),
    'H' => array(
            'region' => 'Hants and Dorset',
            'dvla'  => array(
                'ABCDEFGHJ'      => 'Bournemouth',
                'KLMNOPRSTUVWXY' => 'Portsmouth'
            )
    ),
    'K' => array(
            'region' => '',
            'dvla'  => array(
                'ABCDEFGHJKL'  => 'Luton',
                'MNOPRSTUVWXY' => 'Northampton'
            )
    ),
    'L' => array(
            'region' => 'London',
            'dvla'  => array(
                'ABCDEFGHJ' => 'Wimbledon',
                'KLMNOPRST' => 'Standmore',
                'UVWXY'     => 'Sidcup'
            )
    ),
    'M' => array(
            'region' => 'Manchester and Merseyside',
            'dvla'  => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Manchester'
            )
    ),
    'N' => array(
            'region' => 'Newcastle and North',
            'dvla'  => array(
                'ABCDEFGHJKLMNO' => 'Newcastle upon Tyne',
                'PRSTUVWXY'      => 'Stockton-on-Tees'
            )
    ),
    'O' => array(
            'region' => 'Oxford',
            'dvla'  => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Oxford'
            )
    ),
    'P' => array(
            'region' => 'Preston and Pennines',
            'dvla'  => array(
                'ABCDEFGHJKLMNOPRST' => 'Preston',
                'UVWXY'              => 'Carlisle'
            )
    ),
    'Q' => array(
            'region' => 'Oxford',
            'dvla'  => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Any - used for vehicles of unidentifiable age'
            )
    ),
    'R' => array(
            'region' => 'Reading',
            'dvla'  => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Reading'
            )
    ),
    'S' => array(
            'region' => 'Scotland',
            'dvla'  => array(
                'ABCDEFGHJ' => 'Glasgow',
                'KLMNO'     => 'Edinburgh',
                'PRST'      => 'Dundee',
                'UVW'       => 'Aberdeen',
                'XY'        => 'Inverness'
            )
    ),
    'V' => array(
            'region' => 'Vale of Severn',
            'dvla'  => array(
                'ABCDEFGHJKLMNOPRSTUVWXY' => 'Worcester'
            )
    ),
    'W' => array(
            'region' => 'West Country',
            'dvla'  => array(
                'ABCDEFGHJ'    => 'Exeter',
                'KL'           => 'Truro',
                'MNOPRSTUVWXY' => 'Bristol'
            )
    ),
    'Y' => array(
            'region' => 'Yorkshire',
            'dvla'  => array(
                'ABCDEFGHJK' => 'Leeds',
                'LMNOPRSTU'  => 'Sheffield',
                'VWXY'       => 'Beverley'
            )
    )
);

?>
