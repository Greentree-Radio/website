<?php
session_start();
include_once("config.php");
$reqip = getRealIpAddr();

$songid = preg_replace('#[^0-9]#i', '', $_POST['rsongid']);
$reqname = preg_replace('#[^A-Za-z0-9]#i', '', $_POST['reqname']);
$rmessage = nl2br(htmlentities($_POST['rmessage'], ENT_QUOTES, 'UTF-8'));

if (empty($songid) || empty($reqip)) { 
    echo '<div class="alert alert-danger"><i class="fa fa-minus-square"></i>&nbsp;<strong>DANGER 919</strong>&nbsp;SYSTEM_ERROR: Missing Data to continue.';
	exit();
  } elseif (empty($reqname)) { 
    echo '<div class="alert alert-danger"><i class="fa fa-minus-square"></i>&nbsp;Please add your name to place a request.';
	exit();
  } else { 	

$reqsongID = $songid;
settype($reqsongID, 'integer');

if (empty($reqname)){
$reqname = 'Guest of '.SITE_NAME.'';
}else{
$reqname = $reqname;
}

if (empty($rmessage)){
$reqmsg =  '';
}else{
$reqmsg = $rmessage;
}

$sqlinsertreq = "INSERT INTO `requests` SET `songID`='$reqsongID', `username`='$reqname', `userIP`='$reqip', `message`='$rmessage', `requested`=now();";
$resultreq = mysqli_query($db_conx, $sqlinsertreq);
	
if($resultreq > 0) {
	$sqlselect = mysqli_query($db_conx, "SELECT `artist`, `title` FROM `songs` WHERE `ID`='$reqsongID' ",1);
	while($row = mysqli_fetch_array($sqlselect)){
    $artist = $row["artist"];
    $title = $row["title"];
	}
echo '
<div align="center" style="padding:6px;background-color:#1d252a;color:#aeb3a2;font-size:12px;" >
<h4>Request Successful</h4><br />You\'ve requested <strong>'.$title.'</strong> by <strong>'.$artist.'</strong> to be played.<br />
Thank you '.$reqname.'!<br />'.$reqmsg.'
</div>';
} else {
echo '
<div align="center" style="padding:6px;background-color:#1d252a;color:#aeb3a2;font-size:12px;" >
<strong>ALERT:</strong> SORRY!<br />Something went wrong.
</div>';
}
 }
?>
