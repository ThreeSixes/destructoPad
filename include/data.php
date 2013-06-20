<?php
/* DestructoPad data layer
 * By Josh Lucy <josh{at}lucyindustrial_dawt_com>
 */

class destructoPadData {
    
    /********************
     * Config and setup *
     ********************/
    
    // Constants
    const DP_MODE_MYSQL = 0; // Put the data layer in MySQL mode.
    
    // Data layer mode
    private $dlMode = NULL;
    
    // MySQL configuration
    private $mysqlDbHost = "localhost";
    private $mysqlDbUser = "padProc";
    private $mysqlDbPass = "Blah@ASD4q5FA4asb";
    private $mysqlDbName = "destructoPad";
    
    // Class constructor
    function destructoPadData($t_mode) {
        // Set up a reference to ourself.
        global $glolbalref;
        $glolbalref[] = &$this;
        
        // Set the mode. We're not using this yet.
        $this->dlMode = $t_mode; // Should be one of the DP_MODE_ constants
    }
    
    /*********************
     * Private functions *
     *********************/
    
    // SECTION: MySQL
    
    // Open a connection to our MySQL DB
    // and return the MySQL link ID.
    private function mysqlCreateConn() {
        // Set up return value.
        $retVal['success'] = FALSE;
        $retVal['error'] = NULL;
        $retVla['conn'] = NULL;
        
        // Open a MySQLi connection using our configured parameters
        $retVal['conn'] = new mysqli($this->mysqlDbHost, $this->mysqlDbUser, $this->mysqlDbPass, $this->mysqlDbName);
        if ($retVal['conn']->errno) {
            $retVal['error'] = "MySQL error on connection: " . $retVal['conn']->connect_error . " - " . $retVal['conn']->connect_error;
            print_r($retVal);
        }
        else {
            $retVal['success'] = TRUE;
        }
        
        // Return results.
        return $retVal;
    }
    
    // Close our MySQL connection
    private function mysqlCloseConn($t_conn) {
        // Set up return value
        $retVal['success'] = FALSE;
        $retVal['error'] = NULL;
        
        // Close the connection
        $t_conn->close();
        if ($t_conn->errno) {
            $retVal['error'] = "MySQL error on closing connection: " . $t_conn->errno . " - " . $t_conn->connect_error;
        }
        else {
            $retVal['success'] = TRUE;
        }
        
        // Return results.
        return $retVal;
    }
    
    // Use MySQL to add a pad.
    private function mysqlAddPad($t_hash, $t_expire, $t_data) {
        // Set up our return values.
        $retVal['success'] = FALSE;
        $retVal['error'] = NULL;
        
        // Open the database.
        $openEngine = $this->mysqlCreateConn();
        $dbEngine = $openEngine['conn'];
        
        // Initialize our statement creator.
        $addStmt = $dbEngine->stmt_init();
        
        // Prepare our sproc call and bind variables.
        $addStmt = $dbEngine->prepare("CALL addPad(?, ?, ?)");
        $addStmt->bind_param('sib', $escHash, $t_expire, $escData);
        
        // Escape strings...
        $escHash = $dbEngine->real_escape_string($t_hash);
        $escData = $dbEngine->real_escape_string($t_data);
        
        // Try to execute
        if($addStmt->execute()) {
            $retVal['success'] = TRUE;
        }
        else {
            $retVal['success'] = FALSE;
            $retVal['error'] = "MySQL error on adding pad: " . $dbEngine->errno . " - " . $dbEngine->error;
        }
        
        // Close DB connection properly.
        $dbEngine->close();
        
        // Return results.
        return $retVal;
    }
    
    // Use MySQL to get a pad.
    private function mysqlGetPad() {
        // Set up our return values.
        $retVal['encryptedBlock'] = NULL;
        $retVal['success'] = FALSE;
        $retVal['error'] = NULL;
        
        
        
        // Return results
        return $retVal;
    }
    
    /********************
     * Public functions *
     ********************/
    
    // Generic function to store a newly-created pad
    public function addPad($t_messageID, $t_expire, $t_encryptedPad) {
        // Set up return value... each return value should contain these
        // values.
        $retVal['success'] = FALSE;
        $retVal['error'] = NULL;
        
        // Determine what mode I'm in.
        switch($this->dlMode) {
            // If I'm in MySQL mode
            case self::DP_MODE_MYSQL:
                // Write the data using MySQL
                $retVal = $this->mysqlAddPad($t_messageID, $t_expire, $t_encryptedPad);
                break;
            default:
                // Do nothing since we don't know what to do.
                $retVal['error'] = "Pad add error: invalid data layer mode.";
                break;
        }
        
        // Return the value.
        return $retVal;
    }
    
    // Generic function to retrieve and destroy a stored pad.
    public function getPad($t_messageID) {
        // Set up return value... each return value should contain these
        // values.
        $retVal['encryptedBlock'] = NULL;
        $retVal['success'] = FALSE;
        $retVal['error'] = NULL;
        
        // Determine what mode I'm in.
        switch($this->dlMode) {
            // If I'm in MySQL mode
            case self::DP_MODE_MYSQL:
                // Get the data using MySQL
                echo "DEBUG: In MySQL mode.\n";
                $retVal = $this->mysqlGetPad($t_messageID);
                break;
            default:
                // Do nothing, since we don't know what to do.
                $retVal['error'] = "Pad get error: invalid data layer mode.";
                break;
        }
        
        // Return the value.
        return $retVal;
    }
}