<?php
error_reporting(0);
/* ============================================================================= */
// CONFIG 
/* ============================================================================= */

$stationName	= "Greentree Radio";		//Your Station name

// MySQLi 
$mysql['server']	= "db";				// MySQL Server's IP (and port if not default). Ex: (191.268.1.2 or 191.268.1.2:345).
$mysql['database']	= "radiodj";				// MySQL database name
$mysql['user']		= "root";					// MySQL database username. Usually root.
$mysql['password']	= "root";						// MySQL database password.

$nextLimit		= 5;
$resLimit		= 20;						// How many results to display?
$resDays		= 7;						// On how many days to build the top?
$reqLimit		= 10;						// Limit number of requests per IP
$track_repeat	= 120;						// Don't display the track if it was played in the last X minutes.
$artist_repeat	= 120;						// Don't display the track if the artist was played in the last X minutes.
$def_timezone	= 'America/Chicago';		// Set your time-zone.
$requests		= false;						// turn on/off requests (true or false)

//Shoutcast player
$shoutcast = 'https://shaincast.caster.fm:22223/'; //ex: "192.168.1.1:8000";
$streamurl = 'https://live365.com/station/Greentree-Radio-a50283'; 
//ex: "http://192.168.1.1:8000/listen.pls";
/* ============================================================================= */
// END CONFIG
/* ============================================================================= */

// LOAD MYSQL CLASS
include('db_class.php');
include('functions.php');
?>