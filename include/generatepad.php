<?php
/*
 * DestructoPad - generate pad
 * This file is designed to be an inline include that encrypts the pad hands it over to the data layer, and then presents the user with the URL.
 * By Josh Lucy <josh{AT}lucyindustrial_dawt_com>
 */

 // Get the post data so we can do something useful with it...
 $padData = utf8_encode($POST['pad']);
 
 // Just for giggles, let's dump the pad as a test.
 echo utf8_decode($padData);
?>