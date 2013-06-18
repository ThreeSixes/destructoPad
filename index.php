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

// Determine use-case (compose new note, submit new note, view note, display error)

// Show the header
require "include/header.php";

// Show stuff here depending on use-case.
?>
<span class="functionalDesc">Welcome to DestructoPad.</span>
<div class="warningText">
<span class="warningHeader">Warning:</span>
Blah blah bah..
</div>
<form name="addPad" method="post" action=".">
<textarea name="pad" type="text" cols=80 rows=40 class="padBox" />
</textarea>
<br />
<input name="submit" type="submit" value="Create Pad" class="cpButton" />
</form>
<?php
// Show the footer
require "include/footer.php";
?>
