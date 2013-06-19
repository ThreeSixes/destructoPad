<?php
/*
 * Web UI code for destructoPad.
 * by Josh Lucy <josh{AT}lucyindustrial_dawt_com>
 *
 * TODO:
 *  - Use-case code for UI.
 *  - Create forms for inputting data
 *  - Input checking on inputs
 *  - Include the needed classes depending on use-case.
 *  - Figure out mod_rewrite stuff for pointing at the read use case.
 *  - CSS
 */

// Require the destructoPad library...
require('include/destructoPad.php');
// Create our destructoPad object
$dp = new destructoPad();

// Determine use-case (compose new note, submit new note, view note, display error)
// Looks like someone just attempted to submit a pad...
print_r($_POST);
// Show the header
require('include/header.php');
// Meow.
require('include/newpad.php');
// Show the footer
require ('include/footer.php');

?>
