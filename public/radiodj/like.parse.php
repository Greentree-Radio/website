<?php
session_start();
require_once("config.php");
$reqip = getRealIpAddr();

$songid = preg_replace('#[^0-9]#', '', $_POST['songid']);

if (empty($songid) || empty($reqip)) { 
    echo '<div class="alert alert-danger"><i class="fa fa-minus-square"></i>&nbsp;<strong>DANGER 919</strong>&nbsp;SYSTEM_ERROR: Missing Data to continue.';
	exit();
  } else { 	

$liksongID = $songid;
settype($liksongID, 'integer');

mysqli_query($db_conx, "INSERT INTO `songlikes`(`usrip`, `likes`) VALUES ('$reqip','$liksongID')");
echo '<div class="rdjbuttonrqd"><span class="fa fa-check"></span></div>';
exit();
}
?>