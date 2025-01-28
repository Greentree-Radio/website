<?php
include_once ("config.php");
$reqIP = getRealIpAddr();

if(isset($_GET['keyword'])){
$keyword = trim($_GET['keyword']) ;
$keyword = mysqli_real_escape_string($db_conx, $keyword);

$sqlls = "SELECT * FROM `songs` WHERE `artist` LIKE '%$keyword%' AND (song_type='0') OR `title` LIKE '%$keyword%' AND (song_type='0') ORDER BY `artist` LIMIT 50";
$lsresult = mysqli_query($db_conx, $sqlls);
if($lsresult){
if(mysqli_affected_rows($db_conx)!=0){
while($lsrow = mysqli_fetch_assoc($lsresult)) {
	$lssongID = mysqli_real_escape_string($db_conx, $lsrow['ID']);
	$lsartist = mysqli_real_escape_string($db_conx, $lsrow['artist']);
	$lstitle = mysqli_real_escape_string($db_conx, $lsrow['title']);
	
	$lsmmss = convertTime($lsrow['duration']);
	
	$lsartist = stripslashes($lsartist);
	$lstitle = stripslashes($lstitle);
	
	$lsartistcut = preg_replace('/\s+?(\S+)?$/', '', substr($lsrow['artist'] . ' ', 0, 42)). '';
	$lstitlecut = preg_replace('/\s+?(\S+)?$/', '', substr($lsrow['title'] . ' ', 0, 24)). '...';
	
	$PhpDatels = strtotime($lsrow["date_played"]);
	$lsdplayed = date('d M, Y', $PhpDatels);
	
	if (file_exists('pictures/'.$lsartist.'.jpg') && $lsartist != '') {
		$lsartist_pic = 'pictures/'.$lsartist.'.jpg';
	} else {
		$lsartist_pic = 'assets/img/noimg.jpg';	
	}
	
	$reqbtn = '';
	if(ALLOWREQS == 'YES'){
	$sqlcheckreq = mysqli_query($db_conx, "SELECT * FROM `requests` WHERE `songID`='".$lssongID."' AND `requested` > NOW() - INTERVAL ".REQINT." HOUR");
	if(mysqli_num_rows($sqlcheckreq)) {
	$reqbtn = '<div class="rdjbuttonrqd">REQUESTED</div>';
	}else{
	$reqbtn = '
	<button onclick="document.getElementById(\'iREQ'.$lssongID.'-RDJ\').style.display=\'block\'" class="rdjbutton">REQUEST</button>
	';
	}
	}elseif(ALLOWREQS == 'NO'){
	$reqbtn = '<div class="rdjbuttonrqd">LOCKED</div>';
	}
	
	$likesong = '';
	if(ALLOWLIKES == 'YES'){
	$sqlchecklikes = mysqli_query($db_conx, "SELECT * FROM `songlikes` WHERE `usrip`='".$reqIP."' AND `likes`='".$lssongID."' LIMIT 1");
	if(mysqli_num_rows($sqlchecklikes)) {
	$likesong = '<button type="submit" class="rdjbuttonoff" name="likes'.$lssongID.'"><span class="fa fa-check"></span></button>';
	}else{
	$likesong = '
	<div id="interactionResultsl'.$lssongID.'">
	<form action="javascript:send'.$lssongID.'Like();" name="likes'.$lssongID.'" id="likes'.$lssongID.'" method="post">
	<button type="submit" class="rdjbutton" name="likes'.$lssongID.'"><span class="fa fa-thumbs-up"></span></button>
	</form></div>
<script type="text/javascript">
    $("#likes'.$lssongID.'").submit(function(){$("input[type=submit]", this).attr("disabled", "disabled");});
    function send'.$lssongID.'Like() {
          var songid = "'.$lssongID.'";
          var url = "like.parse.php";
          $.post(url,{songid: songid} , function(data) {
                   $("#interactionResultsl'.$lssongID.'").html(data).show();
                   document.send'.$lssongID.'Like.songid.value="";
          });
    }
</script>
	';
	}
	}elseif(ALLOWLIKES == 'NO'){
	$likesong = '<div class="rdjbuttonrqd"><span class="fa fa-lock"></span></div>';
	}
	
echo '
<ul class="reqil">
  <li style="width:8%;"><img src="'.$lsartist_pic.'" width="100%" style="min-width:70px;min-height:70px;max-width:70px;max-height:70px;" alt="'.$lsartist.'"></li>
  <li style="width:27%;">' . $lsartist . '</li>
  <li style="width:35%;">' . $lstitle . '</li>  
  <li style="width:5%;">' . $lsmmss . '</li>  
  <li style="width:15%;">'.$reqbtn.'</li>
  <li style="width:7%;">'.$likesong.'</li>
</ul>


<!-- The RDJ Modal -->
<div id="iREQ'.$lssongID.'-RDJ" class="rdjmodal">
<span onclick="document.getElementById(\'iREQ'.$lssongID.'-RDJ\').style.display=\'none\'" class="rdjclose" title="Close">&times;</span>

<div class="rdjcontainer">
      <ul class="reqil">
	  <li style="width:24%;"><img src="'.$lsartist_pic.'" style="min-width:110px;min-height:110px;max-width:110px;max-height:110px;" alt="'.$lsartist.'" class="rdjavatar"></li>
	  <li style="width:72%;"><h4>'.$lsartist.'</h4>'.$lstitle.'</li>
	  </ul>
</div>

<div class="rdjcontainer">
<div id="interactionResult'.$lssongID.'">
<form action="javascript:send'.$lssongID.'REQ();" name="req'.$lssongID.'" id="req'.$lssongID.'" method="post">
<input name="reqname'.$lssongID.'" type="text" id="reqname'.$lssongID.'" style="padding:12px;width:80%;" placeholder="Your name" class="rdjinput" />
<textarea name="rmessage'.$lssongID.'" id="rmessage'.$lssongID.'" rows="2" style="padding:12px;width:80%;" placeholder="Add a message to display on site when '.$lsartist.' is played (optional)" class="rdjinput"></textarea>
<button type="submit" class="rdjbutton" style="width:80%;" name="req'.$lssongID.'">Request this song to be played.</button>
</form>
</div>
</div>

</div>

<script type="text/javascript">
var rdjmodal = document.getElementById("iREQ'.$lssongID.'-RDJ");

$("#req'.$lssongID.'").submit(function(){$("input[type=submit]", this).attr("disabled", "disabled");});
function send'.$lssongID.'REQ() {
	  var reqname = $("#reqname'.$lssongID.'");
	  var rmessage = $("#rmessage'.$lssongID.'");
	  var rsongid = "'.$lssongID.'";
	  var url = "requests.parse.php";
      $.post(url,{reqname: reqname.val(), rmessage: rmessage.val(), rsongid: rsongid} , function(data) {
			   $("#interactionResult'.$lssongID.'").html(data).show();
			   document.send'.$lssongID.'REQ.rsongid.value="";
      });
}
</script>
';
}
}else{
echo '
<div class="alert alert-warning alert-dismissable">
  <h4><i class="fa fa-exclamation-triangle"></i>&nbsp;No Results for : "'.$_GET['keyword'].'"</h4>
  <p>Please try a different search.</p>
</div>

';
}
  
}else{
echo '
<div class="alert alert-danger alert-dismissable">
  <h4><i class="fa fa-exclamation-triangle"></i>&nbsp;ERROR: Parameters Missing</h4>
  <p>System error - not your fault.</p>
</div>

';
}
}
?>