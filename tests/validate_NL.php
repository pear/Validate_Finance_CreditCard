<?php

require_once( "Validate/NL.php" );

//Phase 1: Testing zipcode (Validate_NL::postcode)
echo "Testing postcode check\n";
echo validateResult("Correct postcode '1234 AB'", Validate_NL::postcode("1234 AB"), true);
echo ValidateResult("Correct postcode '1234 ab'", Validate_NL::postcode("1234 ab"), true);
echo ValidateResult("Correct postcode '1234AB'", Validate_NL::postcode("1234AB"), true);
echo ValidateResult("Correct postcode '1234ab'", Validate_NL::postcode("1234ab"), true);
echo ValidateResult("Correct postcode '1234aB'", Validate_NL::postcode("1234aB"), true);
echo ValidateResult("Incorrect postcode '123456'", Validate_NL::postcode("123456"), false);
echo ValidateResult("Incorrect postcode '1234'", Validate_NL::postcode("1234"), false);
echo ValidateResult("Incorrect postcode 'AB1234'", Validate_NL::postcode("AB1234"), false);
echo ValidateResult("Incorrect postcode 'Ab12 34'", Validate_NL::postcode("aB12 34"), false);



//Phase 2: Testing phonenumbers
echo "\nTesting phonenumber check (Validate_NL::phonenumber\n";
echo ValidateResult("Correct mobile phonenumber (0612345678)", Validate_NL::phonenumber("0612345678", VALIDATE_NL_PHONENUMBER_TYPE_MOBILE), true);
echo ValidateResult("Correct mobile phonenumber (0031612345678)", Validate_NL::phonenumber("0031612345678", VALIDATE_NL_PHONENUMBER_TYPE_MOBILE), true);
echo ValidateResult("Correct mobile phonenumber (+31612345678)", Validate_NL::phonenumber("+31612345678", VALIDATE_NL_PHONENUMBER_TYPE_MOBILE), true);
echo ValidateResult("Correct normal phonenumber (0101234567)", Validate_NL::phonenumber("0101234567", VALIDATE_NL_PHONENUMBER_TYPE_NORMAL), true);
echo ValidateResult("Correct normal phonenumber (+31101234567)", Validate_NL::phonenumber("+31101234567", VALIDATE_NL_PHONENUMBER_TYPE_NORMAL), true);
echo ValidateResult("Correct normal phonenumber (0031101234567)", Validate_NL::phonenumber("0031101234567", VALIDATE_NL_PHONENUMBER_TYPE_NORMAL), true);
echo ValidateResult("Correct (any) phonenumber (0612345678)", Validate_NL::phonenumber("0612345678", VALIDATE_NL_PHONENUMBER_TYPE_ANY), true);
echo ValidateResult("Correct (any) phonenumber (+31101234567)", Validate_NL::phonenumber("+31101234567", VALIDATE_NL_PHONENUMBER_TYPE_ANY), true);

echo ValidateResult("Incorrect mobile phonenumber (+31101234567)", Validate_NL::phonenumber("+31101234567", VALIDATE_NL_PHONENUMBER_TYPE_MOBILE), false);
echo ValidateResult("Incorrect normal phonenumber (0031612345678)", Validate_NL::phonenumber("0031612345678", VALIDATE_NL_PHONENUMBER_TYPE_NORMAL), false);
echo ValidateResult("Incorrect phonenumber (0101234A67)", Validate_NL::phonenumber("0101234A67", VALIDATE_NL_PHONENUMBER_TYPE_ANY), false);

//Phase 3: testing ssn (SoFi nummer)
echo "\nTesting SSN number check (Validate_NL::SSN)\n";
echo ValidateResult("Incorrect SSN (12345678)", Validate_NL::SSN("12345678"), false);
echo ValidateResult("Incorrect SSN (1234567890)", Validate_NL::SSN("1234567890"), false);
echo ValidateResult("Correct SSN (123456789)", Validate_NL::SSN("123456789"), true);











function validateResult($description, $result, $compareString)
{
    if ((string)$result == (string)$compareString) {
        $ret = "$description - PASSED\n";
    } else {
        $ret = "$description - FAILED\nResult was $result, "
                                        ."expecting $compareString\n";
    }
    return $ret;

}

?>