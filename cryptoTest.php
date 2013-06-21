<?php
/*
 * DestructoPad crypto test code by Josh Lucy <josh{AT}lucyindustrial_dawt_com>
 * 
 */

#Includes/requires
require("include/destructoPad.php");

// Create our destructoPad object.
$dp = new destructoPad();
 
#Inputs for crytpo engine.
$keyAText = $dp->createGUID(); // Just the GUID.
$keyBText = "http://somesite.com/" . $keyAText; // Fake URL, including GUID
$plainText = utf8_encode("http://www.youtube.com/watch?v=L-iepu3EtyE");

$encryptedData = $dp->getEncrypted($keyAText, $keyBText, $plainText);
$decryptedData = $dp->getDecrypted($keyAText, $keyBText, $encryptedData['encryptedBlock']);
$encOverhead = $dp->getOverheadSize(strlen($plainText));

echo "<<< BEGIN CRYPTO TESTS >>>\n\n";
echo " - Inputs -\n";
echo "Original plaintext:   " . $plainText . "\n";
echo "Key A Pre-hash:       " . $keyAText . "\n";
echo "Key B Pre-hash:       " . $keyBText . "\n";
echo "\n - Encryption - \n";
echo "Encrypted Hex:        0x" . bin2hex($encryptedData['encryptedBlock']) . "\n";
echo "Encrypted HMAC Hex:   0x" . bin2hex($encryptedData['HMAC']) . "\n";
echo "\n - Decryption - \n";
echo "Decrypted:            " . $decryptedData['plainText'] . "\n";
echo "Decrypted HMAC Hex:   0x" . bin2hex($decryptedData['calcHMAC']) . "\n";
echo "HMAC good:            ";
echo ($decryptedData['hmacGood'] === TRUE) ? "Yes": "No";
echo "\n";
echo "Original matches:     ";
echo ($decryptedData['plainText'] === $plainText) ? "Yes": "No";
echo "\n\n";
echo "Decrypted Hex:        0x" . bin2hex($decryptedData['plainText']) . "\n";
echo "Original Hex:         0x" . bin2hex($plainText) . "\n";
echo "\n - Stats -\n";
echo "Original Size:        " . strlen($plainText) . " bytes\n";
echo "Ciphertext Overhead:  " . $encOverhead . " bytes\n";
echo "Encrypted Block Size: " . strlen($encryptedData['encryptedBlock']) . " bytes\n";
echo "Overhead + Plaintext: " . ($encOverhead + strlen($plainText)) . " bytes\n";
echo "Block size correct:   ";
echo (strlen($encryptedData['encryptedBlock']) === ($encOverhead + strlen($plainText))) ? "Yes" : "No";
echo "\n";
echo "\n<<< END CRYPTO TESTS >>>\n";
?>
