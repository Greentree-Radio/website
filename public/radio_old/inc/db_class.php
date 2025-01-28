<?php
// MYSQL connect class by Jampie


//MYSQL connection
function db_connect() {
global $mysql;
    // Define connection as a static variable, to avoid connecting more than once 
    static $connection;

    // Try and connect to the database, if a connection has not been established yet
    if(!isset($connection)) {
		$connection = mysqli_connect($mysql['server'],$mysql['user'],$mysql['password'],$mysql['database']);
		//mysql_set_charset('utf8',$connection);
    }

    // If connection was not successful, handle the error
    if($connection === false) {
        // Handle error - notify administrator, log to a file, show an error screen, etc.
        return mysqli_connect_error(); 
    }
    return $connection;
}
//DB Functions

// A select query. $result will be a `mysqli_result` object if successful
function db_query($query,$extern) {
    // Connect to the database
	$connection = db_connect();
    $result = mysqli_query($connection,$query);
    return $result;
}
function insert_id($extern) {
    // Connect to the database
	$connection = db_connect();
    $result = mysqli_insert_id($connection);
    return $result;
}
// Fetch the last error from the database
function db_error() {
    $connection = db_connect();
    return mysqli_error($connection);
}
// Quote and escape value for use in a database query
function db_quote($value) {
    $connection = db_connect();
    return "" . mysqli_real_escape_string($connection,$value) . "";
}
//
function db_select($query,$extern) {
    $rows = array();
    $result = db_query($query,$extern);

    // If query failed, return `false`
    if($result === false) {
        return false;
    }

    // If query was successful, retrieve all the rows into an array
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}
function db_sselect($query,$extern) {
    $result = db_query($query,$extern);
    // If query failed, return `false`
    if($result === false) {
        return false;
    }

    $row = mysqli_fetch_assoc($result);
    
    return $row;
}
?>