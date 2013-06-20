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
if (isset($_POST['submit'])) {
    // The form was submitted with a new pad.
    $thisUseCase = "generatePad";
} elseif (!empty($_GET['targetPad'])) {
    // A user had performed a "get" against us.
    $thisUseCase = "getPad";
}

// Change behavior based on the use-case...
// the default use case is always createPad.
switch($thisUseCase) {
    case "generatePad":
        // Get our header...
        require('include/header.php');
        
        // This bit is responsible for encrypting the pad,
        // generating the GUID and URL, and handing the
        // encrypted pad off to the data layer for storage.
        require('include/generatepad.php');
        
        // Show the footer...
        require ('include/footer.php');
        
        break;
    case "getPad":
        // This is where we return the pad as plain text.
        
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
        // Something went really wrong if we're in this block.
        
        // Get our header...
        require('include/header.php');
        
        // Display a confusing error message.
        echo "      <div class=\"warningText\">\n<span class=\"warningHead\">Unable figure out what the hell you're doing.</span><br />WTF was that? Try your legit action again, or pick a different sploit.\n</div>\n";
        
        // Get our header...
        require('include/header.php');
        break;

}

?>
