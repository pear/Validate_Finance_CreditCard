<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
/**
 * Australian postcode list of PEAR Validate_AU postcodes.txt conversion script
 *
 * Converts the Australia Post postcode list from 
 * http://www1.auspost.com.au/download/pc-full.zip to the PEAR Validate_AU 
 * postcodes.txt list format.
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
 * @package   Validate_AU
 * @author    Dave Hall <dave.hall@skwashd.com>
 * @copyright 2008 Dave Hall
 * @license   http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Validate_AU
 */

function main() {

    // Download
    $source = file_get_contents('http://www1.auspost.com.au/download/pc-full.zip');

    if (!is_writable(dirname(__FILE__))) {
        die("I can't write to " . dirname(__FILE__));
    }

    $path = dirname(__FILE__) . '/pc-full.zip';
    if (!file_put_contents($path, $source)) {
        die("Failed to write archive to " . $path);
    }

    $fp = fopen($path, 'r');

    if (!$fp) {
        die("Couldn't read " . $path);
    }

    //Extract
    $zip = new ZipArchive;
    if (!$zip->open($path)) {
        die("Couldn't read zip " . $path);
    }

    $postcode_path = dirname(__FILE__) . '/postcodes'; 
    $zip->extractTo($postcode_path);
    $zip->close();

    $dir = new DirectoryIterator($postcode_path);

    foreach ($dir as $file) {
        if (!is_file($file)) {
            continue;
        }

        $postcodes = fetch_postcodes_from_file(fopen((string)$file, 'r'));
        

        sort($postcodes, SORT_NUMERIC);
var_dump($postcodes);
        print implode("\n", $postcodes);

        //unlink($postcode_path . '/' . (string)$file);
    }
}

/**
 * Read a file pointer and preg match it for valid
 * postcodes
 *
 * @param resource $fp File to read
 *
 * return string[]
 */
function fetch_postcodes_from_file($fp) {

    $postcodes = array();
    while ($data = fgetcsv($fp)) {
        $line = fgets($fp, 1024);

        $m    = array();
        preg_match('/^"([\d]{4})"/', $line, $m);
        if (count($m)) {
            $postcodes[] = $m[1];
        }
    }
    
    return array_unique($postcodes);
}


main();
?>

