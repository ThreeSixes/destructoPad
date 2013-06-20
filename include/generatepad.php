<?php
/*
 * DestructoPad - generate pad
 * This file is designed to be an inline include that encrypts the pad hands it over to the data layer, and then presents the user with the URL.
 * By Josh Lucy <josh{AT}lucyindustrial_dawt_com>
 */
 echo "Activate error reporting... ";
 ini_set('display_errors',1); 
 error_reporting(E_ALL);
 echo "check pad length... ";
 // Check the size of the post (in a rough fashion) to make sure we can use it.
 if (strlen($_POST['pad']) > 0 && strlen($_POST['pad']) < $dp->getMaxPadSize()) {
    
    // IF this has been satisfied let's load the data layer...
    echo "Bring data layer... ";
    require("include/data.php");
    $dpdl = new destructoPadData(destructoPadData::DP_MODE_MYSQL);
    
    echo "Got data layer... ";
    
    //$dp = new destructoPad();
    
    // If our pad post isn't empty or too large, set the pad data.
    $padData = utf8_encode($_POST['pad']);   
    
    // Generate a GUID
    $newGuid = $dp->createGUID();
    
    // Generate our URL with our current request and the GUID
    $newURL = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"] . "get/" . $newGuid;
    
    // Come up with our expiration time...
    $expireTime = $dp->getPadRandomExprire();
    
    // Generate our encrypted text...
    $encrypted = $dp->getEncrypted($newGuid, $newURL, $padData);
    
    echo "Got " . strlen($encrypted) . " encrypted bytes... ";
    
    echo "Calling addPad()... ";
    
    // Now create the pad in the data layer
    $addWorked = $dpdl->addPad($dp->createHash($newURL), $expireTime, $encrypted);
    
    echo "Called addPad()... ";
    
    if ($addWorked['success'] == FALSE) {
        echo "addPad() failed:: " . $addWorked['error'] . "... ";
    }
    echo "      <br />\n";
    echo "      <br />\n";
    echo "      Your link is:\n";
    echo "      <br />\n";
    echo "      " . $newURL . "\n";
    echo "      <br />\n";
    echo "      Feel free to copy and paste it, but remember once the pad is loaded it will delete itself and will expire if unread in " . $expireTime . " hrs. See below for details.\n";
    echo "      <br />\n";
    echo "      <br />\n";
    
    //Make sure our encrypted data doesn't violate the maximum size constraint.
    if (strlen($encrypted) <= $dp->getMaxPadSize()) {
      // Call our data layer
      
    } else {
        echo "      <div class=\"warningText\">\n<span class=\"warningHead\">Unable to create pad</span><br />It's too large for storage.\n</div>\n";
    }
 } else {
    echo "      <div class=\"warningText\">\n<span class=\"warningHead\">Unable to create pad</span><br />It's either blank or too large for storage.\n</div>\n";
 }
?>