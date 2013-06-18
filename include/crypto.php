<?php
/* DestructoPad cryptography class
 * By Josh Lucy <josh{at}lucyindustrial_dawt_com>
 */

 // TODO:
 // - Check to make sure we have correct values coming from the web page.
 
 class destructoPad {
    
    // Crypto configuration
    private $cipher = "rijndael-256"; // Default: AES-256
    private $mode = "cbc"; // Default: CBC
    private $hashAlgo = "sha256"; // Default: SHA-256, choose based on cipher.
    private $ivSize = 0; // This will hold the calculated IV size given a cipher and mode.
    private $algoBlockSize = 0; // This is the block size of our chosen encryption algorithm.
    private $hmacAlgo = "sha512"; // Default: SHA-512
    
    // Constructor
    function destructoPad() {
        // Set up a reference to ourself.
        global $glolbalref;
        $glolbalref[] = &$this;
        
        // Set our IV size.
        $this->setIVSize();

	// Set our block size.
	$this->setAlgoBlockSize();
        
    }
    
    /*********************
     * Private functions *
     *********************/
    
    // Return the IV size...
    private function setIVSize() {
        // Calculate the necessary IV size.
        $this->ivSize = mcrypt_get_iv_size($this->cipher, $this->mode);
    }

    // Get the block size for a given algorithm
    private function setAlgoBlockSize() {
	$this->algoBlockSize = mcrypt_module_get_algo_block_size($this->cipher);
    }

    // Get message padding size.
    private function getPaddingSize($t_dataSize) {
	$blockSizeMod = $t_dataSize % $this->algoBlockSize;
	return $this->algoBlockSize - $blockSizeMod;
    }
    
    // Generate random padding bytes.
    private function getPadding($t_paddingSize) {
	// String to hold our padding data.
	$padding = "";

	// Generate padding.
	for($i = 0; $i < $t_paddingSize; $i++)
	{
	    $padding .= chr(mt_rand(0,255)); 
	}
	
	return $padding;
    }

    // Hashing function
    private function getHash($t_tbh) {
        return hash($this->hashAlgo, $t_tbh, TRUE);
    }

    // Generate the final en/decryption key.
    private function getFinalKey($t_keyA, $t_keyB) {
        // Build our final key using XOR
        return $t_keyA ^ $t_keyB;
    }

    // Calculate HMAC hash of the message and IV using our final key.
    private function getHmac($t_data, $t_finalKey) {
       // Return the HMAC'd hash.
       return hash_hmac($this->hmacAlgo, $t_data, $t_finalKey, TRUE);
    }  

    // Get the size of the HMAC hash
    private function getHmacSize() {
	// Make a dummy hash and figure out how big it is.
	// Note: there's a different way to do this, but I'm doing
	// this way so when the config changes the system can
	// dynamically adjust.
	return strlen($this->getHmac("X", $this->getHash("Y")));
    }
    
    // Encrypt the message
    private function encryptData($t_data, $t_finalKey) {
        // Generate our IV, sizing it correctly based on cipher and mode.
        $iv = mcrypt_create_iv($this->ivSize, MCRYPT_RAND);

	// Pad the end of the string with random data to prevent oracle padding attacks.
	$paddingSize = $this->getPaddingSize(strlen($t_data));
	
	// Pad our string.
	$paddingData = $this->getPadding($paddingSize);
	$paddedData = $t_data . $paddingData;

        // Encrypt the message
        $encryptedBlock = mcrypt_encrypt($this->cipher, $t_finalKey, $paddedData, $this->mode, $iv);

	// Build the message block.
        $messageBlock = $iv . $encryptedBlock;

	// Calculate the HMAC of the message block.
	$messageBlockHmac = $this->getHmac($messageBlock, $t_finalKey);
 
        // Return binary encrypted data including IV, padding size, and the HMAC Hash.    
        return $iv . chr($paddingSize) . $encryptedBlock . $messageBlockHmac;
    }
    
    // Decrypt the message.
    private function decryptData($t_data, $t_finalKey) {
	// Assume the HMAC hash we calculate doesn't match the one embedded
	// in the message.
	$hmacGood = FALSE;

	// Get the total size of the data we're working wiht.
	$dataSize = strlen($t_data);

	// Figure out the size of the HMAC hash.
	$hmacSize = $this->getHmacSize();

	// Get the size of the HMAC hash so we know where to find it.
	$hmacStart = $dataSize - $hmacSize;

	// Figure out the start of our padding data.
	$paddingSizeStart = $this->ivSize;

	// Figure out how big the ciperhtext is... (the extra 1 is for the padding
	// length  byte.)
	$cipherTextSize = $dataSize - ($this->ivSize + $hmacSize + 1);

	// The ciphertext starts right after the padding size byte.
	$cipherTextStart = $paddingSizeStart + 1;

        // Find the prepended IV, HMAC hash, padding length, and ciphertext.
        $iv = substr($t_data, 0, $this->ivSize);
	$paddingSize = ord(substr($t_data, $paddingSizeStart, 1));
	$embeddedHmacHash = substr($t_data, $hmacStart);
        $cipherText = substr($t_data, $cipherTextStart, $cipherTextSize);
        
	// We don't need the ciphertext anymore so let's forget it
	// to save some memory.
	unset($t_data);

	// Calculate the HMAC of the CipherText and IV.
	$calculatedHmacHash = $this->getHmac($iv . $cipherText, $t_finalKey);
	
	// Check to see if our hashes match.
	if ($calculatedHmacHash === $embeddedHmacHash) {
	    $hmacGood = TRUE;
	}

	// Decrypt the ciphertext.
	$paddedPlaintext = mcrypt_decrypt($this->cipher, $t_finalKey, $cipherText, $this->mode, $iv);

	// Remove padding from decrypted string.
	$plainText = substr($paddedPlaintext, 0, $cipherTextSize - $paddingSize);

	// A bit more memory management.
	unset($paddedPlaintext);

	// Set up our return array.
        $retVal['plainText'] = $plainText;  
	$retVal['hmacGood'] = $hmacGood;

	// Return the array.
	return $retVal;
    }
    
    /********************
     * Public functions *
     ********************/    
    // Get our encryption format overhead values.
    public function getOverheadSize($t_dataSize) {
        return $this->ivSize + 1 + $this->getPaddingSize($t_dataSize) + $this->getHmacSize();
    }

    // Get decrypted data.
    public function getDecrypted($t_keyA, $t_keyB, $t_data) {
        // Data input sanity check.
        
        // If we look good then let's go...
        if(true) {
            // Hash our specified key values...
            $keyAHash = $this->getHash($t_keyA);
            $keyBHash = $this->getHash($t_keyB);
            
            // Generate the enryption key
            $finalKey = $this->getFinalKey($keyAHash, $keyBHash);
            
            return $this->decryptData($t_data, $finalKey);
        }
    }
    
    // Get encrypted data.
    public function getEncrypted($t_keyA, $t_keyB, $t_data) {
        // Make sure we have all the needed values.
        
        // If so let's go to town.
        if(true) {
            // Hash our specified key values...
            $keyAHash = $this->getHash($t_keyA);
            $keyBHash = $this->getHash($t_keyB);
            
            // Generate the enryption key
            $finalKey = $this->getFinalKey($keyAHash, $keyBHash);
            
            // Encrypt our data.
            return $this->encryptData($t_data, $finalKey);
        }
    }
}

?>
