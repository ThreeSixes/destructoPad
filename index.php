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

// Set default use-case:
$thisUseCase = "createPad";

// Determine use-case (compose new note, submit new note, view note, display error)
if ($_POST['submit'] == "Create Pad") {
    // The form was submitted with a new pad.
    $thisUseCase = "generatePad";
} elseif ($_GET['targetPad'] != NULL) {
    // A user had performed a "get" against us.
    $thisUseCase = "getPad";
}

// Change behavior based on the use-case...
// the default use case is always createPad.
switch($thisUseCase) {
    case "generatePad":
        // Get our header...
        require('include/header.php');
        // Show the HTML to create the new pad...
        require('include/newpad.php');
        // Show the footer...
        require ('include/footer.php');
        break;
    case "getPad":
        break;
    case "createPad":
        // Get our header...
        require('include/header.php');
        // Show the HTML to create the new pad...
        require('include/newpad.php');
        // Show the footer...
        require ('include/footer.php');
        break;
    default:
        // Error.
        // Get our header...
        require('include/header.php');
        echo "      WTF was that!?";
        // Get our header...
        require('include/header.php');
        break;

}

?>
