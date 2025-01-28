<?php
require_once("config.php");

$reqIP = getRealIpAddr();

$shuffleQuery = null;

if (SHUFFCUP == TRUE) {
	$shuffleQuery = " ORDER BY RAND()";
}
$upcoming = '';
$bgY = '';
$reqY = '';
$songs_total = '';
$plyd_total = '';
$artists_total = '';
$reqd_total = '';
$sqlque = "SELECT songs.ID, songs.artist, queuelist.songID FROM songs, queuelist WHERE songs.song_type=0 AND songs.ID=queuelist.songID" . $shuffleQuery . " LIMIT 0,4";
$lqueue = mysqli_query($db_conx, $sqlque);
while($qlrow = mysqli_fetch_assoc($lqueue)) {
	$lid = mysqli_real_escape_string($db_conx, $qlrow['ID']);
	$lartist = mysqli_real_escape_string($db_conx, $qlrow['artist']);
	
	$lartist = stripslashes($lartist);
	
	if (file_exists('pictures/'.$lartist.'.jpg') && $lartist != '') {
		$unartist_pic = '<img src="pictures/'.$lartist.'.jpg" class="media-object" style="width:82px;height:82px;object-fit: cover;" alt="'.$lartist.'" title="'.$lartist.'" />';
	} else {
		$unartist_pic = '<img src="assets/img/noimg.jpg" class="media-object" style="width:82px;height:82px;object-fit: cover;" alt="'.$lartist.'" title="'.$lartist.'" />';	
	}
	
	$sqlreqch = "SELECT `songID`, `username`, `message` FROM `requests` WHERE `songID`='".$lid."' AND `requested` > NOW() - INTERVAL 3 HOUR LIMIT 1";
	$result = mysqli_query($db_conx, $sqlreqch);
	if (mysqli_num_rows($result) > 0) {
    while($rinfo = mysqli_fetch_assoc($result)) {
	$reqsongid = mysqli_real_escape_string($db_conx, $rinfo['songID']);
	$requsername = mysqli_real_escape_string($db_conx, $rinfo['username']);
	}
	}
	if(!empty($requsername) && ($reqsongid == $lid)){
		$reqY = '<span style="float:right;background:#000000;position:absolute;line-height:14px;font-family:Arial Black,sans-serif;color:#aeb3b6;font-size:12px;padding:4px;"><span class="fa fa-user-plus"></span></span>';
	}else{
		$reqY = '';
	}
   
   $upcoming .= '
<div class="media" style="background: rgba(0, 0, 0, 0.7);padding:12px;">
<div class="media-left">
'.$unartist_pic.'
</div>
<div class="media-body" style="padding:10px;">
<h6>'.$lartist.'</h6>
<p></p>
</div>'.$reqY.'
</div>
   '; 
   
}
$requsername = '';
$nartist_pic = '';
$reqmsg = '';
$nartist = '';
$ntitle = '';
$sqlnow = "SELECT `ID`, `trackID`, `date_played`, `artist`, `title`, `duration` FROM `history` WHERE `song_type`='0' ORDER BY `date_played` DESC LIMIT 1";
$nqueue = mysqli_query($db_conx, $sqlnow);
while($nrow = mysqli_fetch_assoc($nqueue)) {
	$nsongID = mysqli_real_escape_string($db_conx, $nrow['ID']);
	$ntrackID = mysqli_real_escape_string($db_conx, $nrow['trackID']);
	$nartist = mysqli_real_escape_string($db_conx, $nrow['artist']);
	$ntitle = mysqli_real_escape_string($db_conx, $nrow['title']);
	$dat = $nrow['date_played'];
	
	$nartist = stripslashes($nartist);
	$ntitle = stripslashes($ntitle);
	
	if (file_exists('pictures/'.$nartist.'.jpg') && $nartist != '') {
		$nartist_pic = '<img src="pictures/'.$nartist.'.jpg" width="100%" style="min-height:416px;max-height:416px;" alt="'.$nartist.'" title="'.$nartist.'" />';
	} else {
		$nartist_pic = '<img src="assets/img/noimglrg.jpg" width="100%" style="min-height:416px;max-height:416px;" alt="'.$nartist.'" title="'.$nartist.'" />';	
	}
	
	$sqlreqch = "SELECT `username`, `message` FROM `requests` WHERE `songID`='".$ntrackID."' AND `requested` > NOW() - INTERVAL 2 HOUR LIMIT 1";
	$result = mysqli_query($db_conx, $sqlreqch);
	if (mysqli_num_rows($result) > 0) {
    while($rinfo = mysqli_fetch_assoc($result)) {
	$requsername = mysqli_real_escape_string($db_conx, $rinfo['username']);
	$msg = mysqli_real_escape_string($db_conx, $rinfo['message']);
	$msg = stripslashes($msg);
	}
	$reqmsg = '
	<br /><span class="slider_tittle" style="font-size:14px;color:#f6f6f6;">Requested by&nbsp;'.$requsername.'<br />
	<marquee style="color:#f6f6f6;font-size:16px;font-weight:bold;" direction="left" loop="-1" scrollamount="4">
	' . $msg . '
	</marquee></span>
	';
	}
}
$nowplay = '
<div class="item" style="background: rgba(0, 0, 0, 0.7);padding:4px;">
  '.$nartist_pic.'
  <div class="carousel-caption" style="background-color:#2c3842;margin-bottom:2px;padding:4px;background: rgba(0, 0, 0, 0.5);">
	<h5>'.$nartist.'</h5>
	<p>'.$ntitle.''.$reqmsg.'</p>
  </div>      
</div>
';
$sql_songs = mysqli_query($db_conx, "SELECT `ID` FROM `songs` WHERE `song_type`='0'");
$songs_total = mysqli_num_rows($sql_songs);
?>

<section id="nowplaying" style="padding-top:20px;">
<div class="row">
  <div class="col-sm-8">
      <!-- media playing -->
      <?php echo $nowplay; ?>
  </div>
  <div class="col-sm-4">
  <!-- media upnext -->
  <?php echo $upcoming; ?>
  </div>
</div>
</section>

<section id="pageinfo">
<div class="container text-center">
  <h3><?php echo SITE_NAME; ?> - <?php echo SITE_SLOG; ?></h3>
  <p>With <?php echo $songs_total; ?> songs to choose from we're sure we'll have something you like on <?php echo SITE_NAME; ?></p>
  <div class="row">
    <div class="col-sm-3 reqil">
<?php
$topreq = '';
$ttsongID = '';
$ttartist = '';
$tttitle = '';
$ttartistcut = '';
$tttitlecut = '';
$ttartist_pic = '';
$sqltreq = "SELECT songID, COUNT(*) as ID FROM `requests` GROUP BY `songID` ORDER BY `ID` DESC LIMIT 0,1";
$treq = mysqli_query($db_conx, $sqltreq);
if($treq === FALSE) { echo ''; }
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
	}
}
$topreq = '
<span style="background: rgba(0, 0, 0, 0.7);position:absolute;line-height:14px;font-family:Arial Black,sans-serif;color:#aeb3b6;font-size:12px;padding:4px;">Top Song - Most Requested</span>
<img src="'.$ttartist_pic.'" class="img-responsive"  width="100%" style="max-height:160px;min-height:160px;" alt="'.$ttartist.'">
<p>'.$ttartistcut.'<br />'.$tttitlecut.'</p>
';
echo $topreq;
?>
    </div>
    <div class="col-sm-3 reqil"> 
<?php
$toppld = '';
$pldsongID = '';
$pldartist = '';
$pldtitle = '';
$pldartistcut = '';
$pldtitlecut = '';
$pldartist_pic = '';
$sqltpld = "SELECT `trackID`, COUNT(*) as ID FROM `history` WHERE `song_type`='0' GROUP BY `trackID` ORDER BY `ID` DESC LIMIT 0,1";
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
}
$toppld = '
<span style="background: rgba(0, 0, 0, 0.7);position:absolute;line-height:14px;font-family:Arial Black,sans-serif;color:#aeb3b6;font-size:12px;padding:4px;">Top Song - Most Played</span>
<img src="'.$pldartist_pic.'" class="img-responsive"  width="100%" style="max-height:160px;min-height:160px;" alt="'.$pldartist.'">
<p>'.$pldartistcut.'<br />'.$pldtitlecut.'</p>
';
echo $toppld;
?>
    </div>
    <div class="col-sm-3 reqil">
<?php
$topliked = '';
$numberlikes ='';
$liksartist_pic = '';
$liksartist = '';
$liksartistcut = '';
$likstitlecut = '';
$sqltl = "SELECT `likes`, COUNT(*) as ID FROM `songlikes` GROUP BY `likes` ORDER BY `ID` DESC LIMIT 1";
if($sqltl === FALSE) { $topliked = ''; }
$tlresult = mysqli_query($db_conx, $sqltl);
while($tlrow = mysqli_fetch_assoc($tlresult)) {
	$tlsongID = mysqli_real_escape_string($db_conx, $tlrow['likes']);
	
	$countlikes = mysqli_query($db_conx, "SELECT * FROM `songlikes` WHERE `likes`='".$tlsongID."'");
	$numberlikes = mysqli_num_rows($countlikes);
	
	$sqlliksong = "SELECT * FROM `songs` WHERE `ID`='".$tlsongID."'";
	$resultliksong = mysqli_query($db_conx, $sqlliksong);
	while($liksrow = mysqli_fetch_assoc($resultliksong)) {
	
	$liksartist = mysqli_real_escape_string($db_conx, $liksrow['artist']);
	$likstitle = mysqli_real_escape_string($db_conx, $liksrow['title']);
	
	$liksartist = stripslashes($liksartist);
	$tltitle = stripslashes($likstitle);
	
	$liksartistcut = preg_replace('/\s+?(\S+)?$/', '', substr($liksrow['artist'] . ' ', 0, 42)). '';
	$likstitlecut = preg_replace('/\s+?(\S+)?$/', '', substr($liksrow['title'] . ' ', 0, 32)). '...';

	if (file_exists('pictures/'.$liksartist.'.jpg') && $liksartist != '') {
		$liksartist_pic = 'pictures/'.$liksartist.'.jpg';
	} else {
		$liksartist_pic = 'assets/img/noimglrg.jpg';	
	}
	}
	
}
	$topliked = '
<span style="background: rgba(0, 0, 0, 0.7);position:absolute;line-height:14px;font-family:Arial Black,sans-serif;color:#aeb3b6;font-size:12px;padding:4px;">Top Song - '.$numberlikes.' Likes </span>
<img src="'.$liksartist_pic.'" class="img-responsive" width="100%" style="max-height:160px;min-height:160px;" alt="'.$liksartist.'">
<p>'.$liksartistcut.'<br />'.$likstitlecut.'</p>
';
echo $topliked;
?>
    </div>  
    <div class="col-sm-3 reqil">
<?php
$histlist = '';
$jmartist_pic = '';
$jmartist = '';
$jmartistcut = '';
$jmtitlecut = '';
$sqlxrr = "SELECT `trackID`, `date_played`, `artist`, `title`, `duration` FROM `history` WHERE `ID` != (SELECT MAX(ID) FROM `history`) AND `song_type`='0' ORDER BY `date_played` DESC LIMIT 0,1";
if($sqlxrr === FALSE) { $histlist = ''; }
$jmresult = mysqli_query($db_conx, $sqlxrr);
while($xrrrow = mysqli_fetch_assoc($jmresult)) {
	$jmsongID = mysqli_real_escape_string($db_conx, $xrrrow['trackID']);
	$jmartist = mysqli_real_escape_string($db_conx, $xrrrow['artist']);
	$jmtitle = mysqli_real_escape_string($db_conx, $xrrrow['title']);
	
	$jmartist = stripslashes($jmartist);
	$jmtitle = stripslashes($jmtitle);
	
	$jmartistcut = preg_replace('/\s+?(\S+)?$/', '', substr($xrrrow['artist'] . ' ', 0, 42)). '';
	$jmtitlecut = preg_replace('/\s+?(\S+)?$/', '', substr($xrrrow['title'] . ' ', 0, 32)). '...';

	if (file_exists('pictures/'.$jmartist.'.jpg') && $jmartist != '') {
		$jmartist_pic = 'pictures/'.$jmartist.'.jpg';
	} else {
		$jmartist_pic = 'assets/img/noimglrg.jpg';	
	}
}
	$histlist = '
<span style="background: rgba(0, 0, 0, 0.7);position:absolute;line-height:14px;font-family:Arial Black,sans-serif;color:#aeb3b6;font-size:12px;padding:4px;">We\'ve just Played</span>
<img src="'.$jmartist_pic.'" class="img-responsive" width="100%" style="max-height:160px;min-height:160px;" alt="'.$jmartist.'">
<p>'.$jmartistcut.'<br />'.$jmtitlecut.'</p>
';
echo $histlist;
?>
    </div>  
  </div>
</div>
</section>

<section id="artistson">
<div class="container text-center">    
  <h3>Artists on <?php echo SITE_NAME; ?></h3>
  <br>
  <div class="row">

<?php
$files = glob("pictures/*.*");
$maxImages = 6;
for ($i=1; $i<=count($files) && $i<=$maxImages; $i++) {
$num = $files[array_rand($files)];
$name = explode(".", basename($num));
echo '
<div class="col-sm-2">
  <img src="'.$num.'" class="img-responsive" width="100%" style="max-width:150px;max-height:80px;background: rgba(0, 0, 0, 0.3);padding:4px;" alt="'.$name['0'].'">
</div>
'."";
}
?>
  </div>
</div><br>
</section>
	