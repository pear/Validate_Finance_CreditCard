<?php

/**
* Some simple tests for Validate::AT
*/

require_once('Validate/AT.php');

function doAssert($should, $is)
{
    echo ($should == $is) ? "PASSED\n" : "FAILED\n";
}

doAssert(true, Validate_AT::postcode(7033));
doAssert(true, Validate_AT::postcode(7000));
doAssert(true, Validate_AT::postcode(4664));
doAssert(true, Validate_AT::postcode(2491));
doAssert(false, Validate_AT::postcode(1000));
doAssert(false, Validate_AT::postcode(9999));
doAssert(false, Validate_AT::postcode('abc'));
doAssert(false, Validate_AT::postcode('a7000'));

doAssert(true, Validate_AT::ssn('4298 02-12-82'));
doAssert(true, Validate_AT::ssn('1508101050'));
doAssert(false, Validate_AT::ssn(1508101051));
doAssert(false, Validate_AT::ssn(4290021282));
doAssert(false, Validate_AT::ssn('21 34 23 12 74'));

?>