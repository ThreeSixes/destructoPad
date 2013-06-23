<?php
/*
 * DestructoPad - generate pad page
 * This file is designed to be an inline include that encrypts the pad hands it over to the data layer, and then presents the user with the URL.
 * By Josh Lucy <josh{AT}lucyindustrial_dawt_com>
 *
 * NOTES:
 *  - The MySQL scripts to create the database, users, and procs is under ./protected/mysqlCreate.sql. Make sure you change the pws in
 *    the script and in the data layer code if you're using MySQL.
 *  - A significant part of this code depends on MOD_REWRITE functioning as designed. See the documentation under ./protected
 */

 // Error display code...
 //ini_set('display_errors', 1); 
 //error_reporting(E_ALL);
 
 // Check the size of the post (in a rough fashion) to make sure we can use it.
 if (strlen($_POST['pad']) > 0 && strlen($_POST['pad']) < $dp->getMaxPadSize()) {
    
    // IF this has been satisfied let's load the data layer...
    require("include/data.php");
    $dpdl = new destructoPadData(destructoPadData::DP_MODE_MYSQL);
    
    // If our pad post isn't empty or too large, set the pad data.
    $padData = utf8_encode($_POST['pad']);   
    
    // Generate a GUID
    $newGuid = $dp->createGUID();
    
    // Generate our URL with our current request and the GUID
    $newURL = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"] . "?targetPad=" . $newGuid;
    $userURL = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] . "?targetPad=" . $newGuid;
    
    // Come up with our expiration time...
    $expireTime = $dp->getPadRandomExprire();
    
    // Generate our encrypted text...
    $encrypted = $dp->getEncrypted($newURL, $newGuid, $padData);
    
    //Make sure our encrypted data doesn't violate the maximum size constraint.
    if (strlen($encrypted['encryptedBlock']) <= $dp->getMaxPadSize()) {
        // Now create the pad in the data layer
        $addWorked = $dpdl->addPad($dp->createHash($newURL), $expireTime, $encrypted['encryptedBlock']);
        
        // If the pad add worked, then let's give our user the good news and URL.
        // We don't have the URL enclosed in a link because if the generator of the message
        // clicks the link it gets nuked as designed.
        if ($addWorked['success'] == TRUE) {
            echo "      <br />\n";
            echo "      Your link is:\n";
            echo "      <br />\n";
            echo "      " . $userURL . "\n";
            echo "      <br />\n";
            echo "      HMAC for end-to-end verification: " . bin2hex($encrypted['HMAC']);
            echo "      <br />\n";
            echo "      Feel free to copy and paste it, but remember once the pad is loaded it will delete itself and will expire if unread in " . $expireTime . " hrs. See below for details.\n";  
            echo "      <br />\n";
            echo "      <br />\n";
        } else {
            echo "      <div class=\"warningText\">\n<span class=\"warningHead\">Unable to create pad</span><br />" . $addWorked['error'] . "\n</div>\n";
        }
    
    } else {
        echo "      <div class=\"warningText\">\n<span class=\"warningHead\">Unable to create pad</span><br />It's too large for storage.\n</div>\n";
    }
 } else {
    echo "      <div class=\"warningText\">\n<span class=\"warningHead\">Unable to create pad</span><br />It's either blank or too large for storage.\n</div>\n";
 }
?>