<?php
/* ============================================================================= */
// RADIO DJ WEB BY JAMPIE
/* ============================================================================= */

include('inc/config.php');
$path = "";
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	  <meta name="keywords" content="">
    <title><?php echo $stationName; ?></title>
    <!-- Adding Favicon -->
    <link rel="shortcut icon" href="favicon.ico">
    <!-- Linking style sheets -->
    <link rel="stylesheet" href="font-awesome/css/font-awesome.css" >
	<link rel="stylesheet" href="css/style.css" >

  <!-- adding script files -->
    <script src="js/jquery-2.0.3.js"></script>
	<script src="js/jquery.countdownTimer.js"></script>
	<script src="js/menu.js"></script>
	<script src="js/main.js"></script>
	<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
	<script src="js/jquery.dialogextend.min.js"></script>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
 <script>
   function popitup(url) 
   {
    newwindow=window.open(url,'name','height=600,width=800,screenX=400,screenY=350');
    if (window.focus) {newwindow.focus()}
    return false;
   }
   </script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
 </head>
<body>
<div class="header">
    <div class="container-main pad-header">
      <div class="bcol-100 menu">
                <span class="menu-text">+ menu</span>

<style>

</style>
	<ul class="menu-bar">
		<li><a href="/" style="color: white;">Greentree Radio</a>
		<li><a href="index.php"><i class="fa fa-space fa-home"></i>Home</a></li>
		<li><a href="javascript:void();" onClick="return popitup('<?php echo $streamurl; ?>');"><i class="fa fa-space fa-music"></i>Listen</a></li>
		<li><a href="top_songs.php"><i class="fa fa-space fa-music"></i>Top Songs</a></li>
		<li><a href="top_albums.php"><i class="fa fa-space fa-music"></i>Top Albums</a></li>
	  <li><a href="list.php"><i class="fa fa-space fa-music"></i>Song Request</a></li>
	</ul>
      </div>
      <div class="clear"></div>
    </div>
</div>