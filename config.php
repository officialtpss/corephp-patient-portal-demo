<?php

    /** Start session */
    session_start();

    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '1234');
    define('DB_NAME', 'corePhp');
    
    /** Attempt to connect to MySQL database */
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    /** Check connection */
    if($conn === false){
        die("ERROR: Could not connect. " . $conn->connect_error);
    }
    
    /** make connection global */
    global $conn;
?>