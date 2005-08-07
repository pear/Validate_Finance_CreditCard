--TEST--
uri.phpt: Unit tests for Validate::uri()
--FILE--
<?php
// $Id$
// Validate test script
$noYes = array('NO', 'YES');
require 'Validate.php';

echo "Test Validate::uri()\n";

$uris = array(
        // with no options (no domain_check and no allowed_schemes
        'not @ goodurl123' , // NOK
        'http://www.ics.uci.edu/pub/ietf/uri/#Related' , // OK
        'http://user:password@www.ics.uci.edu:8080/pub/ietf/uri;rfc2396?test=ok&end=next#Related' , // OK
        '//127.0.0.1', // OK
        '//127.0.333.1', // NOK
        'http://user:password@127.0.0.1:8080/pub/ietf/uri;rfc2396?test=ok&end=next#Related' , // OK
        '127.0.0.1', // NOK
        // Try dns lookup
        array('//example.org', '', true), // OK
        array('//example.gor', '', true), // NOK
        // Try schemes lookup
        array('//example.org', 'ftp,http'), // NOK
        array('http://example.org', 'ftp,http'), // OK
        array('http://example.org', 'ftp,http', true) // OK
    );

foreach ($uris as $uri) {
    if (is_array($uri)) {
        $options = array();
        $options['domain_check'] = isset($uri[2]) ? $uri[2] : false;
        $options['allowed_schemes'] = $uri[1] ? explode(',', $uri[1]) : null;
        echo "{$uri[0]}: schemes({$uri[1]}) with". ($options['domain_check'] ? '' : 'out') . ' domain check : '.
            $noYes[Validate::uri($uri[0], $options )]."\n";
    } else {
        echo "{$uri}: ".
            $noYes[Validate::uri($uri)]."\n";
    }
}
?>
--EXPECT--
Test Validate::uri()
not @ goodurl123: NO
http://www.ics.uci.edu/pub/ietf/uri/#Related: YES
http://user:password@www.ics.uci.edu:8080/pub/ietf/uri;rfc2396?test=ok&end=next#Related: YES
//127.0.0.1: YES
//127.0.333.1: NO
http://user:password@127.0.0.1:8080/pub/ietf/uri;rfc2396?test=ok&end=next#Related: YES
127.0.0.1: NO
//example.org: schemes() with domain check : YES
//example.gor: schemes() with domain check : NO
//example.org: schemes(ftp,http) without domain check : NO
http://example.org: schemes(ftp,http) without domain check : YES
http://example.org: schemes(ftp,http) with domain check : YES
