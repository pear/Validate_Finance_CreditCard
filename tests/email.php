<?php
require_once 'PHPUnit.php';
require 'Validate.php';

class Validate_Email_Test extends PHPUnit_TestCase
{
    var $email = array(
        // Try dns lookup
        array('example@example.org', true) => true,
        array('example@fluffffffrefrffrfrfrfrfrfr.is', false) => false,
        // with out the dns lookup
        'example@fluffffffrefrffrfrfrfrfrfr.is' => true,
        
        // Some none english chars
        'hæjjæ@homms.com' => true,
        'postmaster@tüv.de' => true,

        // Test for various ways with _
        'mark_@example.com' => true,
        '_mark@example.com' => true,
        'mark_foo@example.com' => true,

        // Test for various ways with -
        'mark-@example.com' => true,
        '-mark@example.com' => true,
        'mark-foo@example.com' => true,

        // Test for various ways with .
        'mark.@example.com' => false,
        '.mark@example.com' => false,
        'mark.foo@example.com' => true,

        // Test for various ways with ,
        'mark,@example.com' => false,
        ',mark@example.com' => false,
        'mark,foo@example.com' => false,

        // Test for various ways with :
        'mark:@example.com' => false,
        ':mark@example.com' => false,
        'mark:foo@example.com' => false,

        // Test for various ways with ;
        'mark;@example.com' => false,
        ';mark@example.com' => false,
        'mark;foo@example.com' => false,

        // Test for various ways with |
        'mark|@example.com' => true,
        '|mark@example.com' => true,
        'mark|foo@example.com' => true,
    );

    function Validate_Email_Test($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function testEmail()
    {
        foreach ($this->email as $email => $expected_result) {
            if (is_array($email)) {
                $email = $email[0];
                $bool = $email[1];
            } else {
                $bool = false;
            }
            $r = Validate::email($email, $bool);
            $this->assertEquals($r, $expected_result);
        }
    }
}

// runs the tests
$suite = new PHPUnit_TestSuite('Validate_Email_Test');
$result = PHPUnit::run($suite);
// prints the tests
echo $result->toString();
?>