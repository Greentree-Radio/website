<?php 
require_once("config.php");

$topreq = '';
$reqd_total = '';
$ttsongID = '';
$ttartist = '';
$tttitle = '';
$ttartistcut = '';
$tttitlecut = '';
$ttartist_pic = '';
$sqltreq = "SELECT songID, COUNT(*) as ID FROM `requests` GROUP BY `songID` ORDER BY `ID` DESC LIMIT 0,10";
$treq = mysqli_query($db_conx, $sqltreq);
if($treq === FALSE) { $topreq = ''; }
while($rtreq = mysqli_fetch_assoc($treq)) {
	
	$trsongID = mysqli_real_escape_string($db_conx, $rtreq['songID']);
	
	$sqlri2 = "SELECT * FROM `songs` WHERE `ID`='$trsongID'";
	$resultri2 = mysqli_query($db_conx, $sqlri2);
	while($tt2row = mysqli_fetch_assoc($resultri2)) {
	$ttsongID = mysqli_real_escape_string($db_conx, $tt2row["ID"]);
	$ttartist = $tt2row["artist"];
	$tttitle = mysqli_real_escape_string($db_conx, $tt2row["title"]);
	
	$ttartistcut = preg_replace('/\s+?(\S+)?$/', '', substr($tt2row['artist'] . ' ', 0, 42)). '';
	$tttitlecut = preg_replace('/\s+?(\S+)?$/', '', substr($tt2row['title'] . ' ', 0, 32)). '...';
	
	if (file_exists('pictures/'.$ttartist.'.jpg') && $ttartist != '') {
		$ttartist_pic = 'pictures/'.$ttartist.'.jpg';
	} else {
		$ttartist_pic = 'assets/img/noimglrg.jpg';	
	}
		
	$sql_reqd = mysqli_query($db_conx, "SELECT `ID` FROM `requests` WHERE `songID`='$ttsongID'");
	$reqd_total = mysqli_num_rows($sql_reqd);
	}

$topreq .= '
<div style="background: rgba(0, 0, 0, 0.7);padding:4px;margin-bottom:4px;">
<span style="background: rgba(0, 0, 0, 0.7);position:absolute;line-height:14px;font-family:Arial Black,sans-serif;color:#aeb3b6;font-size:12px;padding:4px;">Requested: '.$reqd_total.' times</span>
<img src="'.$ttartist_pic.'" class="img-responsive"  width="100%" style="max-height:160px;min-height:160px;" alt="'.$ttartist.'">
<p>'.$ttartistcut.'<br />'.$tttitlecut.'</p>
</div>
';
}
$likedlist = '';
$tlnumberlikes = '';
$tlsongID = '';
$tlartist = '';
$tltitle = '';
$tlartistcut = '';
$tltitlecut = '';
$tlartist_pic = '';
$sqltl = "SELECT `likes`, COUNT(*) as `lID` FROM `songlikes` GROUP BY `likes` ORDER BY `lID` DESC LIMIT 0, 10";
$tlresult = mysqli_query($db_conx, $sqltl);
if($tlresult === FALSE) { $likedlist = ''; }
while($tl1row = mysqli_fetch_assoc($tlresult)) {
	
	$tlsongID = mysqli_real_escape_string($db_conx, $tl1row['likes']);
	
	$sqlri2 = "SELECT * FROM `songs` WHERE `ID`='$tlsongID'";
	$resultri2 = mysqli_query($db_conx, $sqlri2);
	while($tlrow = mysqli_fetch_assoc($resultri2)) {
	$tlsongID = mysqli_real_escape_string($db_conx, $tlrow["ID"]);
	$tlartist = $tlrow["artist"];
	$tltitle = mysqli_real_escape_string($db_conx, $tlrow["title"]);
	
	$tlartistcut = preg_replace('/\s+?(\S+)?$/', '', substr($tlrow['artist'] . ' ', 0, 26)). '';
	$tltitlecut = preg_replace('/\s+?(\S+)?$/', '', substr($tlrow['title'] . ' ', 0, 32)). '...';
	
	if (file_exists('pictures/'.$tlartist.'.jpg') && $tlartist != '') {
		$tlartist_pic = 'pictures/'.$tlartist.'.jpg';
	} else {
		$tlartist_pic = 'assets/img/noimglrg.jpg';	
	}
	$counttllikes = mysqli_query($db_conx, "SELECT * FROM `songlikes` WHERE `likes`='".$tlsongID."'");
	$tlnumberlikes = mysqli_num_rows($counttllikes);
	}

$likedlist .= '
<div style="background: rgba(0, 0, 0, 0.7);padding:4px;margin-bottom:4px;">
<span style="background: rgba(0, 0, 0, 0.7);position:absolute;line-height:14px;font-family:Arial Black,sans-serif;color:#aeb3b6;font-size:12px;padding:4px;">'.$tlnumberlikes.' likes</span>
<img src="'.$tlartist_pic.'" class="img-responsive"  width="100%" style="max-height:160px;min-height:160px;" alt="'.$tlartist.'">
<p>'.$tlartistcut.'<br />'.$tltitlecut.'</p>
</div>
';
}

$toppld = '';
$tpld_total = '';
$pldsongID = '';
$pldartist = '';
$pldtitle = '';
$pldartistcut = '';
$pldtitlecut = '';
$pldartist_pic = '';
$sqltpld = "SELECT `trackID`, COUNT(*) as `nID` FROM `history` WHERE `song_type`='0' GROUP BY `trackID` ORDER BY `nID` DESC LIMIT 10";
$tpld = mysqli_query($db_conx, $sqltpld);
if($tpld === FALSE) { $toppld = ''; }
while($rtpld = mysqli_fetch_assoc($tpld)) {
	
	$tpsongID = mysqli_real_escape_string($db_conx, $rtpld['trackID']);
	
	$sqlpld = "SELECT * FROM `songs` WHERE `ID`='$tpsongID'";
	$resultpld = mysqli_query($db_conx, $sqlpld);
	while($pldrow = mysqli_fetch_assoc($resultpld)) {
	$pldsongID = mysqli_real_escape_string($db_conx, $pldrow["ID"]);
	$pldartist = $pldrow["artist"];
	$tpld_total = $pldrow["count_played"];
	$pldtitle = mysqli_real_escape_string($db_conx, $pldrow["title"]);
	
	$pldartistcut = preg_replace('/\s+?(\S+)?$/', '', substr($pldrow['artist'] . ' ', 0, 42)). '';
	$pldtitlecut = preg_replace('/\s+?(\S+)?$/', '', substr($pldrow['title'] . ' ', 0, 32)). '...';
	
	if (file_exists('pictures/'.$pldartist.'.jpg') && $pldartist != '') {
		$pldartist_pic = 'pictures/'.$pldartist.'.jpg';
	} else {
		$pldartist_pic = 'assets/img/noimglrg.jpg';	
	}
	}

$toppld .= '
<div style="background: rgba(0, 0, 0, 0.7);padding:4px;margin-bottom:4px;">
<span style="background: rgba(0, 0, 0, 0.7);position:absolute;line-height:14px;font-family:Arial Black,sans-serif;color:#aeb3b6;font-size:12px;padding:4px;">Played: '.$tpld_total.' times</span>
<img src="'.$pldartist_pic.'" class="img-responsive"  width="100%" style="max-height:160px;min-height:160px;" alt="'.$pldartist.'">
<p>'.$pldartistcut.'<br />'.$pldtitlecut.'</p>
</div>
';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo SITE_NAME; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div id="maincont">
<header id="header">
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="header_top">
        <div class="header_top_left">
          <ul class="top_nav">
        <li><a href="#">Home</a></li>
        <li class="active"><a href="topsongs.php">Top Songs</a></li>
        <li><a href="requests.php">Requests</a></li>
        <li><a href="recently.php">Recently</a></li>
        <?php echo TUNEIN; ?>
      </ul>
          </ul>
        </div>
        <div class="header_top_right">
          <a href="/" class="logo"><img src="<?php echo SITE_LOGO; ?>" width="100%" style=" max-width:230px;max-height:56px;" alt="<?php echo SITE_NAME; ?>"></a>
        </div>
      </div>
    </div>
  </div>
</header>


<section id="pageinfo">
<div class="container text-center">    
  <h3>Top Tens on <?php echo SITE_NAME; ?></h3>
  <br>
  <div class="row">
    <div class="col-sm-4"><h2>Requested</h2>
    <?php echo $topreq; ?>
    </div>
    <div class="col-sm-4"><h2>Played</h2>
    <?php echo $toppld; ?>
    </div>
    <div class="col-sm-4"><h2>Liked</h2>
    <?php echo $likedlist; ?>
    </div>
  </div>
</div>
</section>



<?php echo FOOTER; ?>
</div>
<!-- YOUR USE OF THIS WEBSCRIPT IS FOR USE WITH RADIODJ (RADIODJ.RO) AND IS BROUGHT TO YOU BY STEWARTSWEBWORKS.COM FREE WITH THE LIMITATION THAT YOU LEAVE THE POWERED BY AND DEVELOPED BY LINKS IN PLACE - THANK YOU -->
</body>
</html>