<?php 
require_once("config.php");
$reqIP = getRealIpAddr();

$sqlxrr = "SELECT COUNT(*) as num FROM `songs` WHERE `enabled`='1' AND `song_type`='0'";
$xrrresult = mysqli_query($db_conx, $sqlxrr);
$row = mysqli_fetch_row($xrrresult);
$rows = $row[0];
$page_rows = REQPROWS;
$last = ceil($rows/$page_rows);
if($last < 1){
	$last = 1;
}
$pagenum = 1;
if(isset($_GET['pn'])){
	$pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}
if ($pagenum < 1) { 
    $pagenum = 1; 
} else if ($pagenum > $last) { 
    $pagenum = $last; 
}
$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
$sqlxrr = "SELECT `ID`, `artist`, `title`, `duration`, `date_played`, `artist_played` FROM `songs` WHERE `enabled`='1' AND `song_type`='0' ORDER BY `artist` ASC $limit";
$xrrresult = mysqli_query($db_conx, $sqlxrr);
$textline1 = "<b>$rows</b> Tracks";
$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";
$paginationCtrls = '';
if($last != 1){
	if ($pagenum > 1) {
        $previous = $pagenum - 1;
		$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>';
		for($i = $pagenum-3; $i < $pagenum; $i++){
			if($i > 0){
		        $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
			}
	    }
    }
	$paginationCtrls .= '<li><a href="#" class="active">'.$pagenum.'</a></li>';
	for($i = $pagenum+1; $i <= $last; $i++){
		$paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'">'.$i.'</a></li>';
		if($i >= $pagenum+3){
			break;
		}
	}
    if ($pagenum != $last) {
        $next = $pagenum + 1;
        $paginationCtrls .= '<li><a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>';
    }
}
$requestlist = '';
while($xrrrow = mysqli_fetch_assoc($xrrresult)) {
	$ttsongID = $xrrrow['ID'];
	$ttartist = mysqli_real_escape_string($db_conx, $xrrrow['artist']);
	$tttitle = mysqli_real_escape_string($db_conx, $xrrrow['title']);
	
	$mmss = convertTime($xrrrow['duration']);
	
	$ttartist = stripslashes($ttartist);
	$tttitle = stripslashes($tttitle);
	
	$artistcut = preg_replace('/\s+?(\S+)?$/', '', substr($xrrrow['artist'] . ' ', 0, 42)). '';
	$titlecut = preg_replace('/\s+?(\S+)?$/', '', substr($xrrrow['title'] . ' ', 0, 24)). '...';
	
	$PhpDate2 = strtotime($xrrrow["date_played"]);
	$dplayed = date('d M, Y', $PhpDate2);
	
	if (file_exists('pictures/'.$ttartist.'.jpg') && $ttartist != '') {
		$artist_pic = 'pictures/'.$ttartist.'.jpg';
	} else {
		$artist_pic = 'assets/img/noimg.jpg';	
	}
	
	$reqbtn = '';
	if(ALLOWREQS == 'YES'){
	$sqlcheckreq = mysqli_query($db_conx, "SELECT * FROM `requests` WHERE `songID`='".$ttsongID."' AND `requested` > NOW() - INTERVAL ".REQINT." HOUR");
	if(mysqli_num_rows($sqlcheckreq)) {
	$reqbtn = '<div class="rdjbuttonrqd">REQUESTED</div>';
	}else{
	$reqbtn = '
	<button onclick="document.getElementById(\'iREQ'.$ttsongID.'-RDJ\').style.display=\'block\'" class="rdjbutton">REQUEST</button>
	';
	}
	}elseif(ALLOWREQS == 'NO'){
	$reqbtn = '<div class="rdjbuttonrqd">LOCKED</div>';
	}
	
	$likesong = '';
	if(ALLOWLIKES == 'YES'){
	$sqlchecklikes = mysqli_query($db_conx, "SELECT * FROM `songlikes` WHERE `usrip`='".$reqIP."' AND `likes`='".$ttsongID."' LIMIT 1");
	if(mysqli_num_rows($sqlchecklikes)) {
	$likesong = '<button type="submit" class="rdjbuttonoff" name="likes'.$ttsongID.'"><span class="fa fa-check"></span></button>';
	}else{
	$likesong = '
	<div id="interactionResultsl'.$ttsongID.'">
	<form action="javascript:send'.$ttsongID.'Like();" name="likes'.$ttsongID.'" id="likes'.$ttsongID.'" method="post">
	<button type="submit" class="rdjbutton" name="likes'.$ttsongID.'"><span class="fa fa-thumbs-up"></span></button>
	</form></div>
<script type="text/javascript">
    $("#likes'.$ttsongID.'").submit(function(){$("input[type=submit]", this).attr("disabled", "disabled");});
    function send'.$ttsongID.'Like() {
          var songid = "'.$ttsongID.'";
          var url = "like.parse.php";
          $.post(url,{songid: songid} , function(data) {
                   $("#interactionResultsl'.$ttsongID.'").html(data).show();
                   document.send'.$ttsongID.'Like.songid.value="";
          });
    }
</script>
	';
	}
	}elseif(ALLOWLIKES == 'NO'){
	$likesong = '<div class="rdjbuttonrqd"><span class="fa fa-lock"></span></div>';
	}
	
$requestlist .= '
<ul class="reqil">
  <li style="width:8%;"><img src="'.$artist_pic.'" width="100%" style="min-width:70px;min-height:70px;max-width:70px;max-height:70px;" alt="'.$ttartist.'"></li>
  <li style="width:27%;">' . $ttartist . '</li>
  <li style="width:35%;">' . $tttitle . '</li>  
  <li style="width:5%;">' . $mmss . '</li>  
  <li style="width:15%;">'.$reqbtn.'</li> 
  <li style="width:7%;">'.$likesong.'</li>
</ul>


<!-- The RDJ Modal -->
<div id="iREQ'.$ttsongID.'-RDJ" class="rdjmodal">
<span onclick="document.getElementById(\'iREQ'.$ttsongID.'-RDJ\').style.display=\'none\'" class="rdjclose" title="Close">&times;</span>

<div class="rdjcontainer">
      <ul class="reqil">
	  <li style="width:24%;"><img src="'.$artist_pic.'" style="min-width:110px;min-height:110px;max-width:110px;max-height:110px;" alt="'.$ttartist.'" class="rdjavatar"></li>
	  <li style="width:72%;"><h4>'.$ttartist.'</h4>'.$tttitle.'</li>
	  </ul>
</div>

<div class="rdjcontainer">
<div id="interactionResult'.$ttsongID.'">
<form action="javascript:send'.$ttsongID.'REQ();" name="req'.$ttsongID.'" id="req'.$ttsongID.'" method="post">
<input name="reqname'.$ttsongID.'" type="text" id="reqname'.$ttsongID.'" style="padding:6px;width:80%;" placeholder="Your name" class="rdjinput" />
<textarea name="rmessage'.$ttsongID.'" id="rmessage'.$ttsongID.'" rows="2" style="padding:6px;width:80%;" placeholder="Add a message to display on site when '.$ttartist.' is played (optional)" class="rdjinput"></textarea>
<button type="submit" class="rdjbutton" style="width:80%;" name="req'.$ttsongID.'">Request this song to be played.</button>
</form>
</div>
</div>

</div>

<script type="text/javascript">
var rdjmodal = document.getElementById("iREQ'.$ttsongID.'-RDJ");

$("#req'.$ttsongID.'").submit(function(){$("input[type=submit]", this).attr("disabled", "disabled");});
function send'.$ttsongID.'REQ() {
	  var reqname = $("#reqname'.$ttsongID.'");
	  var rmessage = $("#rmessage'.$ttsongID.'");
	  var rsongid = "'.$ttsongID.'";
	  var url = "requests.parse.php";
      $.post(url,{reqname: reqname.val(), rmessage: rmessage.val(), rsongid: rsongid} , function(data) {
			   $("#interactionResult'.$ttsongID.'").html(data).show();
			   document.send'.$ttsongID.'REQ.rsongid.value="";
      });
}
</script>
';
}
$sql_songs = mysqli_query($db_conx, "SELECT `ID` FROM `songs` WHERE `song_type`='0'");
$songs_total = mysqli_num_rows($sql_songs);
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
<script src="assets/js/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$("#live_search_input").keyup(function(){
var live_search_input = $(this).val();
var dataString = 'keyword='+ live_search_input;
if(live_search_input.length>1){
$.ajax({
type: "GET",
url: "requests.live.php",
data: dataString,
beforeSend:  function() {
$('input#live_search_input').addClass('loading');},
success: function(server_response){
$('#searchresultdata').html(server_response).show();
if ($('input#live_search_input').hasClass("loading")) {
$("input#live_search_input").removeClass("loading");} 
}
});
}return false;
});
});
</script>
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
        <li class="active"><a href="requests.php">Requests</a></li>
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
  <h3>Request on <?php echo SITE_NAME; ?></h3>
  <br>
  <div class="row">
    <div class="col-sm-12">
    <input  name="query" type="text" id="live_search_input" style="font-size:16px;width:100%;min-width:240px;padding:8px;border:#e6e6e6 solid 1px;border-radius:5px 5px 5px 5px;" placeholder="Search <?php echo $songs_total; ?> songs by Artist or Title" />
    <hr />
    <div id="searchresultdata" class="xr-liveresuts" align="left" style="margin-bottom:12px;"> </div>
    <?php echo $requestlist; ?>
    </div>
    <div align="center" style="padding:20px;">
      <nav>
        <ul class="pagination">
          <?php echo $paginationCtrls; ?>
        </ul>
      </nav>
    </div>
  </div>
</div>
</section>


<section id="pageinfo">
<div class="container text-center"> 
  <div class="row">
    <div class="col-sm-4">Request Available
    <div class="rdjbutton">REQUEST</div>
    </div>
    <div class="col-sm-4">Song Requested (<?php echo REQINT; ?> Hour interval)
    <div class="rdjbuttonrqd">REQUESTED</div>
    </div>
    <div class="col-sm-1">Like
    <button type="submit" class="rdjbutton"><span class="fa fa-thumbs-up"></span></button>
    </div>
    <div class="col-sm-1">Logged
    <div class="rdjbuttonrqd"><span class="fa fa-check"></span></div>
    </div>
    <div class="col-sm-1">Liked
    <button type="submit" class="rdjbuttonoff"><span class="fa fa-check"></span></button>
    </div>
    <div class="col-sm-1">Locked
    <div class="rdjbuttonrqd"><span class="fa fa-lock"></span></div>
    </div>
  </div>
</div>
</section>

<?php echo FOOTER; ?>
<!-- YOUR USE OF THIS WEBSCRIPT IS FOR USE WITH RADIODJ (RADIODJ.RO) AND IS BROUGHT TO YOU BY STEWARTSWEBWORKS.COM FREE WITH THE LIMITATION THAT YOU LEAVE THE POWERED BY AND DEVELOPED BY LINKS IN PLACE - THANK YOU -->
</div>
</body>
</html>
