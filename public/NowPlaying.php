<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Now Playing</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" media="all" href="style.css">
</head>
<body>
<div class="wrapper">
<br />

<?php
 error_reporting(1);
/*
v3.0
NOW PLAYING PHP SCRIPT
Latest Update: July, 31 2018

This is a script that will display the playing, the upcoming and the played tracks.

=============================================================================

DISCLAIMER:

THE SCRIPT IS PROVIDED ON AN “AS IS” AND “AS AVAILABLE” BASIS WITHOUT ANY WARRANTIES OF ANY KIND.
WE HEREBY DISCLAIM ALL WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE WARRANTY OF TITLE, MERCHANTABILITY,
NON INFRINGEMENT OF THIRD PARTIES’ RIGHTS, AND FITNESS FOR PARTICULAR PURPOSE. 

IN NO EVENT SHALL WE BE LIABLE FOR ANY DAMAGES WHATSOEVER (INCLUDING, WITHOUT LIMITATION,
INCIDENTAL AND CONSEQUENTIAL DAMAGES, LOST PROFITS, OR DAMAGES RESULTING FROM LOST DATA OR
BUSINESS INTERRUPTION) RESULTING FROM THE USE OR INABILITY TO USE THE SITE AND THE CONTENT,
WHETHER BASED ON WARRANTY, CONTRACT, TORT (INCLUDING NEGLIGENCE), OR ANY OTHER LEGAL THEORY,
EVEN IF A WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.

=============================================================================
EDIT BELOW
=============================================================================*/
date_default_timezone_set('America/Chicago');

$mysqli_server				= "192.168.2.112";			// MySQL Server's IP (and port if not default). Ex: (191.268.1.2 or 191.268.1.2:345).
$mysqli_database			= "radiodj";      		// MySQL database name
$mysqli_user				= "root";				// MySQL database username. Ussually root.
$mysqli_pass				= "root";			// MySQL database password.
$mysqli_port				= "8889";				// MySQL port number
$shufleUpcoming				= false;					// Don't show the correct order of upcoming tracks
$nextLimit					= 1;					// How many upcoming tracks to display?
$resLimit					= 25;					// How many history tracks to display?

/*
=============================================================================
END EDIT
=============================================================================*/

function db_conn() {
    global $opened_db, $mysqli_server, $mysqli_user, $mysqli_pass, $mysqli_database, $mysqli_port;

    @$opened_db = mysqli_connect($mysqli_server, $mysqli_user, $mysqli_pass);
    
    if (!$opened_db) {
		echo "<p><strong>The database connection cannot be established! Please check if the login details are correct!</strong></p></div>";
        die(mysqli_error());
		
        
    } else {
        @mysqli_select_db($opened_db, $mysqli_database)
            or die(mysqli_error());
    }
}
    
function db_close($opened_db) {
    @mysqli_close($opened_db);
}

function convertTime(init $seconds) {	
	$H = floor($seconds / 3600);
	$i = ($seconds / 60) % 60;
	$s = $seconds % 60;
   return sprintf("%02d:%02d:%02d", $H, $i, $s);
}

?>
<div class="container">
    <div class="row">
<table class="table table-striped" border="0" cellspacing="0" cellpadding="5">

<?php

db_conn();
$shuffleQuery = null;

If ($shufleUpcoming == True) {
    $shuffleQuery = " ORDER BY RAND()";
}

$nextquery = "SELECT songs.ID, songs.artist, queuelist.songID FROM songs, queuelist WHERE songs.song_type=0 AND songs.ID=queuelist.songID" . $shuffleQuery . " LIMIT 0," . $nextLimit;
$resultx = mysqli_query($opened_db, $nextquery);

if (!$resultx) {
    echo mysqli_error();
    exit;
}

if (mysqli_num_rows($resultx) > 0) {
    
    // If there tracks in the playlist, we show them
    $inc = 0;

    echo " <tr>" . "\n";
    echo "  <td class='header'><h2>Up next</h2></td>\n";
    echo " </tr>" . "\n";

    echo " <tr>" . "\n";
    echo " <td>";

    while($rowx = mysqli_fetch_array($resultx)) {
        echo htmlspecialchars($rowx['artist'], ENT_QUOTES);
        
        //if the current track is not the last, we put a separator
        if ($inc < (mysqli_num_rows($resultx) -1)) {
            echo ", ";
        }
        
        $inc += 1;
    }

    echo "</td>" . "\n";
    echo "</tr>" . "\n";
    echo "</table>";
} 


//Uncomment this if you would like to show a message when no track is prepared.
else {
    echo "<table class='table table-striped'>";
    echo " <tr>" . "\n";
    echo "  <td class=\"header_live\">Station Unavailable</td>\n";
    echo " </tr>" . "\n";
    
    echo " <tr>" . "\n";
    echo "  <td>Our station is currently offline.</td>\n";
    echo " </tr>" . "\n";
    echo "</table>";
}


// ======================== //

$query = "SELECT `ID`, `date_played`, `artist`, `title`, `duration` FROM `history` WHERE `song_type` = 0 ORDER BY `date_played` DESC LIMIT 0," . ($resLimit+1);

$result = mysqli_query($opened_db, $query);

if (!$result) {
    echo mysqli_error();
    exit;
}

if (mysqli_num_rows($result) == 0) {
    exit;
}

$inc = 0;

while($row = mysqli_fetch_assoc($result)) {
    /*
    if ($inc == 0) {
        echo "<table class='table table-striped'>";
        echo " <tr>" . "\n";
        echo "   <td class=\"header_live\">NOW PLAYING</td>\n";
        echo " </tr>" . "\n";

        echo " <tr>" . "\n";
        echo "  <td class=\"playing_track\"><strong>" . htmlspecialchars($row['artist'], ENT_QUOTES) . " <br> " . htmlspecialchars($row['title'], ENT_QUOTES) . " [" . convertTime(init $row['duration']) . "]</strong></td>\n";
        echo " </tr>" . "\n";
        echo "</table>";

        if ($resLimit > 0) {
            echo "<table class='table table-striped'>";
            echo " <tr>" . "\n";
            echo "  <td class=\"header_live\">RECENTLY PLAYED SONGS</td>\n";
            echo " </tr>" . "\n";

        }

    } else {
        */

        if ($resLimit > 0) {
            echo " <tr>" . "\n";
            echo "  <td>" . date('H:i:s', strtotime($row['date_played'])) . " - " . htmlspecialchars($row['artist'], ENT_QUOTES) . " - " . htmlspecialchars($row['title'], ENT_QUOTES) . " [" . convertTime($row['duration']) . "]</td>\n";
            echo " </tr>" . "\n";
        }
    }
    $inc += 1;
/*
}
*/

@mysqli_free_result($resultx);
db_close($opened_db);

?>
</table>
</div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</div>