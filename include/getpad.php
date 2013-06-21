<?php
/*
 * DestructoPad - get pad page
 * This file is designed to be an inline include that parses the request URL, and presents the requested pad to the user in a decrypted form.
 * By Josh Lucy <josh{AT}lucyindustrial_dawt_com>
 *
 * NOTES:
 *  - A significant part of this code depends on MOD_REWRITE functioning as designed. See the documentation under ./protected
 */

 // Error display code...
 //ini_set('display_errors', 0); 
 //error_reporting(E_ALL);
 
 // Check the size of the post (in a rough fashion) to make sure we can use it.
 if (strlen($_GET['targetPad']) > 0) {
    // Set up or destructoPad instance...
    require("include/destructoPad.php");
    $dp = new destructoPad();
    
    // Get our hash from the URL...
    // DUMMY CODE HERE FOR NOW
    $targetURL = "";
    $targetGUID = ""; // URL GUID
    
    // Get our keys...
    $keyA = $dp->createHash($targetURL);
    $keyB = $dp->createHash($targetGUID);
    
    // IF this has been satisfied let's load the data layer...
    require("include/data.php");
    $dpdl = new destructoPadData(destructoPadData::DP_MODE_MYSQL);
    
    // Look up our pad...
    $gotPad = $dpdl->getPad($keyA);
    
    // Did we get a pad?
    if($gotPad['success'] === TRUE) {
        // If the pad exists decrypt it.
        $decryptedPad = $dp->getDecrypted($keyA, $keyB, $gotPad['encryptedBlock']);
        
        // Did the resultant pad pass HMAC validation?
        if($decryptedPad['hmacGood'] === TRUE) {
            // Get our MIME type and charset settings.
            $mimeHeader = $dp->getMimeAndCharset();
            
            // Build our HTTP response header...
            $respHeader = "Content-Type: " . $mimeHeader['mime'] . "; charset=" . $mimeHeader['charset']; // FIX ME!
            
            // Set the HTTP MIME type header
            header($respHeader);
            
            // Present the decrypted pad to the user.
            echo $decryptedPad['plainText'];
            echo "\n\nHMAC for verification: " . bin2hex($decryptedPad['calcHMAC']);
            
        }
        else {
            // Decryption failure - HMAC verification failed.
            include("include/header.php");
            echo "      <div class=\"warningText\">\n<span class=\"warningHead\">Pad decryption failed at the server.</span><br />The message has been modified or corrupted. The pad will not be displayed.\n</div>\n";
            include("include/footer.php");    
        }
        
        
    }
    else {
        // Present error page.
        include("include/header.php");
        echo "      <div class=\"warningText\">\n<span class=\"warningHead\">Couldn't find the pad!</span><br />The requested pad either does not exist or has already been read and burned.\n</div>\n";
        include("include/footer.php");
    }
    
    
 }
    // Warning text example...
    //echo "      <div class=\"warningText\">\n<span class=\"warningHead\">Unable to create pad</span><br />It's either blank or too large for storage.\n</div>\n";
 
?>