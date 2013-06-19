<?php
/*
 * DestructoPad - generate pad
 * This file is designed to be an inline include that encrypts the pad hands it over to the data layer, and then presents the user with the URL.
 * By Josh Lucy <josh{AT}lucyindustrial_dawt_com>
 */
 
 include "destructoPad.php";
 $dp = new destructoPad();
 
 // Get the post data so we can do something useful with it...
 $padData = utf8_encode($_POST['pad']);
 
 $newGuid = $dp->createGUID();
 $newURL = $_SERVER['REQUEST_URI'] . $newGuid;
 $encrypted = $dp->getEncrypted($newGuid, $newURL, $padData);
 // Just for giggles, let's dump the pad as a test.
 echo $newGuid . "<br />";
 echo $newURL . "<br />";
 echo $encrypted . "<br />";
 echo $padData . "<br />";
?>