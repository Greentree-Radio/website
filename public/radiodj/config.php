<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////
// Fill in all the information below to your settings for the website to connect to your database
// 
// If you have any queries please contact me : emma@stewartswebworks.com
//
// YOUR USE OF THIS WEBSCRIPT IS FOR USE WITH RADIODJ (RADIODJ.RO)
// AND IS BROUGHT TO YOU BY STEWARTSWEBWORKS.COM FREE WITH THE LIMITATION
// THAT YOU LEAVE THE POWERED BY AND DEVELOPED BY LINKS IN PLACE - THANK YOU
//
////////////////////////////////////////////////////////////////////////////////////////////////////////

///////// SITE INFORMATION /////////////////////////////////////////////////////////////////////////////
define('SITE_NAME', 'RadioDJ'); // Website name - XperienceRewind
define('SITE_SLOG', 'All the Hits'); // 70s 80s internet radio
define('ADMIN_EMAIL', ''); // Site admin email (emma@stewartswebworks.com)
define('SITE_URL', ''); // Website url https://xperiencerewind.co.uk/
define('SITE_LOGO', 'assets/img/logo.png');
define('ORS_LNUMB', ''); // Your Online Radio Stream Licence Number


///////// TUNE IN BUTTONS ///////////////////////////////////////////////////////////////////////
// Add your Server details for people to listen to your broadcast : You can remove if you don't have all buttons.
/*
 <li><a href="http://SERVER:PORT" target="_blank"><img src="../images/xrpbtn.png" width="20" height="18" /> Mobile</a></li>
define('TUNEIN', '
<li><a href="/mobile.html" target="_blank"><img src="assets/img/xrpbtn.png" width="12" height="12" /> Mobile</a></li>
<li><a href="XperienceRewind.asx"><img src="assets/img/wmpbtn.png" width="12" height="12" /> WMP</a></li>
<li><a href="http://webcast-connect.net/premium/uk3/8390/listen.pls" ><img src="assets/img/wabtn.png" width="12" height="12" /> Winamp</a></li>
<li><a href="http://webcast-connect.net/premium/uk3/8390/listen.ram" ><img src="assets/img/qtbtn.png" width="12" height="12" /> Quicktime</a></li>		   
');
*/
///////// DISPLAY SETTINGS ///////////////////////////////////////////////////////////////////////
define('SHUFFCUP', FALSE); // Shuffle upcoming tracks (TRUE / FALSE)
define('PLDDISPLAYLIM', '40'); // Recently played (40)
define('REQPROWS', '30'); // Show xx Results on Request page
define('REQINT', '1'); // Request interval. (HOURS(How many hours before a song is available for request after a request is placed))
define('ALLOWLIKES', 'YES'); // Allow users to LIKE songs (YES | NO)
define('ALLOWREQS', 'YES'); // Allow users to REQUEST songs (YES | NO)

///////// MYSQLI DATABASE INFORMATION //////////////////////////////////////////////////////////////////
// You're MySQLi database for the website
define('SITE_HOST', 'db'); // localhost
define('SITE_DB', 'radiodj'); // Database name
define('SITE_UNAME', 'root'); // Database username
define('SITE_PASS', 'root'); // Database password

///////// FUNCTIONS - DO NOT EDIT BELOW /////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////

function convertTime($seconds) { $H = floor($seconds / 3600); $i = ($seconds / 60) % 60;
$s = $seconds % 60;return sprintf("%02d:%02d", $i, $s);}
function getRealIpAddr() {if (!empty($_SERVER['HTTP_CLIENT_IP'])){ $ip=$_SERVER['HTTP_CLIENT_IP'];}elseif
(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];}else{
$ip=$_SERVER['REMOTE_ADDR'];}return $ip;}

$db_conx = @mysqli_connect(SITE_HOST,SITE_UNAME,SITE_PASS,SITE_DB);
define('PBY', 'POWERED BY <a style="color:#d2d2d2;" href="https://radiodj.ro" target="_blank">RADIODJ</a>');
define('DBY', 'Developed By <a style="color:#d2d2d2;" href="https://stewartswebworks.com" target="_blank">StewartsWebWorks.com</a>');
if(!$db_conx) { die(require_once("assets/offline.php")); }
$sqlcsl = mysqli_query($db_conx, "SELECT * FROM `songlikes` LIMIT 1");
if(!$sqlcsl) { echo '
<div style="background-color: #ffdddd;border-left: 6px solid #f44336;border-right: 6px solid #f44336;padding:10px;width:100%;max-width:1200px;margin-right: auto;margin-left: auto;color:#f44336;">
<strong>DANGER - Song likes table not detected!</strong><br /><br />This script will not work without the relevant tables in your database.<br /><br />
Please goto <a href="'.SITE_URL.'setup.php">'.SITE_URL.'setup.php</a> to install the tables.
<br /><br />
</div>'; }

define('FOOTER', '<footer id="footer"><p>&copy; '.SITE_NAME.' '.date("Y").' All rights reserved. '.ORS_LNUMB.'<br /> '.PBY.' - '.DBY.'</p></footer>');

