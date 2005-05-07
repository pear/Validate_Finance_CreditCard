--TEST--
email.phpt: Unit tests for 
--FILE--
<?php
// $Id$
// Validate test script
$noYes = array('NO', 'YES');
require 'Validate.php';

echo "Test Validate_Email\n";

$emails = array(
        // Try dns lookup
        array('example@example.org', true), // OK
        array('example@fluffffffrefrffrfrfrfrfrfr.is', true), // NOK
        array('example@fluffffffrefrffrfrfrfrfrfr.is', false), // OK
        // with out the dns lookup
        'example@fluffffffrefrffrfrfrfrfrfr.is', // OK
        
        // Some none english chars, those should fail until we fix the IDN stuff
        'hæjjæ@homms.com', // NOK
        'postmaster@tüv.de', // NOK

        // Test for various ways with _
        'mark_@example.com', // OK
        '_mark@example.com', // OK
        'mark_foo@example.com', // OK

        // Test for various ways with -
        'mark-@example.com', // OK
        '-mark@example.com', // OK
        'mark-foo@example.com', // OK

        // Test for various ways with .
        'mark.@example.com', // NOK
        '.mark@example.com', // NOK
        'mark.foo@example.com', // OK

        // Test for various ways with ,
        'mark,@example.com', // NOK
        ',mark@example.com', // NOK
        'mark,foo@example.com', // NOK

        // Test for various ways with :
        'mark:@example.com', // NOK
        ':mark@example.com', // NOK
        'mark:foo@example.com', // NOK

        // Test for various ways with ;
        'mark;@example.com', // NOK
        ';mark@example.com', // NOK
        'mark;foo@example.com', // NOK

        // Test for various ways with |
        'mark|@example.com', // OK
        '|mark@example.com', // OK
        'mark|foo@example.com', // OK

        // Test for various ways with double @
        'mark@home@example.com', // NOK
        'mark@example.home@com', // NOK
        'mark@example.com@home' // NOK
    );

foreach ($emails as $email) {
    if (is_array($email)) {
    echo "{$email[0]}: with". ($email[1] ? '' : 'out') . ' domain check : '.
        $noYes[Validate::email($email[0], $email[1])]."\n";
    } else {
    echo "{$email}: ".
        $noYes[Validate::email($email)]."\n";
    }
}
?>
--EXPECT--
Test Validate_Email
example@example.org: with domain check : YES
example@fluffffffrefrffrfrfrfrfrfr.is: with domain check : NO
example@fluffffffrefrffrfrfrfrfrfr.is: without domain check : YES
example@fluffffffrefrffrfrfrfrfrfr.is: YES
hæjjæ@homms.com: NO
postmaster@tüv.de: NO
mark_@example.com: YES
_mark@example.com: YES
mark_foo@example.com: YES
mark-@example.com: YES
-mark@example.com: YES
mark-foo@example.com: YES
mark.@example.com: NO
.mark@example.com: NO
mark.foo@example.com: YES
mark,@example.com: NO
,mark@example.com: NO
mark,foo@example.com: NO
mark:@example.com: NO
:mark@example.com: NO
mark:foo@example.com: NO
mark;@example.com: NO
;mark@example.com: NO
mark;foo@example.com: NO
mark|@example.com: YES
|mark@example.com: YES
mark|foo@example.com: YES
mark@home@example.com: NO
mark@example.home@com: NO
mark@example.com@home: NO
