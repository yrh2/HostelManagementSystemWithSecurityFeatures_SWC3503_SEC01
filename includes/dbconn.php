<?php
    $dbuser="root";
    $dbpass="";
    $host="localhost";
    $db="hostelmsphp";
    
    $mysqli =new mysqli($host,$dbuser, $dbpass, $db);

    if ($mysqli->connect_errno) {
        die("Connection error: " . $mysqli->connect_error);
    }
    
    return $mysqli;

?>