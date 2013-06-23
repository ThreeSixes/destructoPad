<?php
/* DestructoPad pad expiration script - this file is designed to be run once an hour by CRON or AT.
 * By Josh Lucy <josh{at}lucyindustrial_dawt_com>
 */

 // Error display code...
 //ini_set('display_errors', 1); 
 //error_reporting(E_ALL);

 // Require our data layer...
 require('../include/data.php');
 
 // This is where se set our data layer mode...
 $targetMode = destructoPadData::DP_MODE_MYSQL;
 
 // If you're using MySQL change these to match a user with execute
 // permissions on the expirePad() sproc.
 $mysqlUser = "padUpdateProc";
 $mysqlPass = "Blah@ASD4q5FA4asb";
  
 function presentResults($t_result) {
    // Did we have a successful expire?
    if($t_result['success'] === TRUE) {
	// Excellent.
	echo "Pad expiration ran successfully.\n";
	
	// Shiny! Exit with a success code.
	exit(0);
    }
    else {
	// Bummer. Dump error and exit with a non-zero code.
	echo "Pad expiration failed. " . $t_result['error'];
	
	// Exit with a failure code.
	exit(1);
    }
 }
 
 // Set default fail.
 $retVal['success'] = FALSE;
 $retVal['error'] = NULL;

 // Based on our target mode execute any special voodoo and then present the output to the user.
 switch ($targetMode) {
    case destructoPadData::DP_MODE_MYSQL:
	// Load the data layer
	$dpdl = new destructoPadData(destructoPadData::DP_MODE_MYSQL);
	
	// For MySQL we need to change the creds, and if it works exprire the pads.
	$dpdl->overrideMysqlCreds($mysqlUser, $mysqlPass) ? $retVal = $dpdl->expirePad(): $retVal['error'] = "Failed to change the MySQL creds.";
	
	// Attempt to 
	$retVal = $dpdl->expirePad();
	
	// Present results.
	presentResults($retVal);
	break;
    default:
	// Flag the operation as a failure with an error message and present it.
	$retVal['error'] = "Failed to call the expire pad proc: Invalid mode.";
	
	// Present results.
	presentResults($retVal);
	break;
 }
 
 
 
 
 ?>