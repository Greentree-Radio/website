<?php 
require_once("config.php");

$i=1;
$toppld = '';
$pldsongID = '';
$pldartist = '';
$pldtitle = '';
$pldartistcut = '';
$pldtitlecut = '';
$pldartist_pic = '';
$sqltpld = "SELECT `trackID`, `date_played`, `artist`, `title`, `duration` FROM `history` WHERE `ID` != (SELECT MAX(ID) FROM `history`) AND `song_type`='0' ORDER BY `date_played` DESC LIMIT 0,".PLDDISPLAYLIM."";
$tpld = mysqli_query($db_conx, $sqltpld);
if($tpld === FALSE) { echo ''; }
while($rtpld = mysqli_fetch_assoc($tpld)) {
	
	$tpsongID = mysqli_real_escape_string($db_conx, $rtpld['trackID']);
	
	$sqlpld = "SELECT * FROM `songs` WHERE `ID`='$tpsongID'";
	$resultpld = mysqli_query($db_conx, $sqlpld);
	while($pldrow = mysqli_fetch_assoc($resultpld)) {
	$pldsongID = mysqli_real_escape_string($db_conx, $pldrow["ID"]);
	$pldartist = $pldrow["artist"];
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
<div class="col-sm-3">
<span style="background: rgba(0, 0, 0, 0.7);position:absolute;line-height:14px;font-family:Arial Black,sans-serif;color:#aeb3b6;font-size:12px;padding:4px;">'.$i.'</span>
<img src="'.$pldartist_pic.'" class="img-responsive"  width="100%" style="max-height:160px;min-height:160px;" alt="'.$pldartist.'">
<p>'.$pldartistcut.'<br />'.$pldtitlecut.'</p>
</div>
';
$i++;
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
        <li><a href="topsongs.php">Top Songs</a></li>
        <li><a href="requests.php">Requests</a></li>
        <li class="active"><a href="recently.php">Recently</a></li>
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
  <h3>Previously on <?php echo SITE_NAME; ?></h3>
  <br>
  <div class="row">
  <?php echo $toppld; ?>
  </div>
</div>
</section>


<?php echo FOOTER; ?>
</div>
<!-- YOUR USE OF THIS WEBSCRIPT IS FOR USE WITH RADIODJ (RADIODJ.RO) AND IS BROUGHT TO YOU BY STEWARTSWEBWORKS.COM FREE WITH THE LIMITATION THAT YOU LEAVE THE POWERED BY AND DEVELOPED BY LINKS IN PLACE - THANK YOU -->
</body>
</html>