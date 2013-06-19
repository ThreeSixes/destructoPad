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

// Show the header
require('include/header.php');

// Show stuff here depending on use-case.
?>
    <span class="functionalDesc">Welcome to DestructoPad.<br />Type the message you wish to convey into the text box below, click the "Create Pad" button, and you will be presented with a one-time URL that can be sent to another person.</span>
    <br />
    <div class="warningText">
        <span class="warningHeader">Warning:</span>
        <ol>
            <li>This tool is designed to work like Pastebin, but it has an auto-destruct feature when the pad URL is opened. It can only be opened once and will be subsequently deleted.</li>
            <li>Be careful about the information you place in the pad because it can be compromised or opened by an unintended actor.</li>
            <li>Although an honest effort is made to encrypt the pads on the server it's still possible for your communciations to be compromised, and encrypted data isn't necessarily safe from all actors, especially state actors.</li>
            <li>Be aware that if the communications between this server and you are compromised your pads may fall into the wrong hands inadvertantly.</li>
            <li>Snice a pad is automatically deleted when opened there is a risk of that happening if a browser or application attempts to generate a preview of the link, etc.</li>
        </ol>
    </div>
    <br />
    <form name="addPad" method="post" action="">
        <textarea name="pad" type="text" class="padBox" />
        </textarea>
        <br />
        <input name="submit" type="submit" value="Create Pad" class="cpButton" />
    </form>

<?php
// Show the footer
require ('include/footer.php');
?>
