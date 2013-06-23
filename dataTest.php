<?php
/*
 * DestructoPad data layer test code by Josh Lucy <josh{AT}lucyindustrial_dawt_com>
 * 
 */

#Includes/requires
require("include/data.php");

// Configure our test inputs.
$targetMode = destructoPadData::DP_MODE_MYSQL; // This should be one of the data layer constants.
$targetPadID = "xxx"; // This value should be something atypical for the input to begin with.
$targetExpire = 10;   // Value for expiration. This test has not yet been implemented.
$targetPad = "Testing 1, 2, 3..."; // Value that's human-readable for easy verification...

// Create our destructoPad object in MySQL mode...
$dpdlMySQL = new destructoPadData($targetMode);

// Attempt to add a pad with some random BS data.
$addRet = $dpdlMySQL->addPad($targetPadID, $targetExpire, $targetPad);

// TODO: Add logic to test addition of data.

// TODO: Add logic to test return and deletion of data.


?>
