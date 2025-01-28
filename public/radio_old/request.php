<?php
include('inc/config.php');
$reqIP = getRealIpAddr();
$error = 0;
if($_GET['req'] == 'off') $requests = false;

if(isset($_GET['song'])){
	if($_GET['song'] != "") {
		
			$reqid = db_quote($_GET['song']);
			//track information
			$songinfo = db_select("SELECT * FROM songs WHERE ID='{$reqid}' LIMIT 1");
			
			//track is already requested?
			$recheck = db_select("SELECT COUNT(*) AS num FROM `requests` WHERE `songID`='$reqid' AND `played`=0");
			if($recheck[0]['num'] > 0){
				$error = 3;
			}
			$recheck = db_select("SELECT COUNT(*) AS num FROM `queuelist` WHERE `songID`='$reqid'");
			if($recheck[0]['num'] > 0){
				$error = 5;
			}
			if($error == 0){
			//user has reached the request limit?
			$recheck2 = db_select("SELECT COUNT(*) AS num FROM `requests` WHERE `userIP`='$reqIP' AND DATE(`requested`) = DATE(NOW())");
			
			if($recheck2[0]['num'] >= $reqLimit){
				$error = 4;
				$reccount = $recheck2[0]['num'];
			}
		}
?>
<script type="application/javascript">
function GetDetailsFromitunes(album_NameTitle){
//alert(album_NameTitle);
album_NameTitle = album_NameTitle.replace(/ /gi,'+');
//alert(album_NameTitle);
if(album_NameTitle!=""){
var url =  "http://itunes.apple.com/search?term="+album_NameTitle+"&country=US&media=music&entity=song&limit=1&lang=en_us&version=2";
	jQuery.getJSON(url + "&callback=?", null, function(data) {
			if(data.results[0]) {
				var coverURL      = data.results[0].artworkUrl100;
				$('#cover').html("<img src='"+coverURL+"'>");
			}
	});
}
}
GetDetailsFromitunes("<?php echo utf8_encode($songinfo[0]['artist']); ?> <?php echo utf8_encode($songinfo[0]['title']); ?>");
</script>
<div class="dialoginfo">
<table class="bordered">
<tr><th scope="col" colspan="3">Track Information</th></tr>
<tr>
<td width="90">Artist</td><td><?php echo utf8_encode($songinfo[0]['artist']); ?></td><td width="99" rowspan="5" ><span id="cover"></span></td>
</tr>
<tr>
<td>Title</td><td><?php echo utf8_encode($songinfo[0]['title']); ?></td>
</tr>
<tr>
<td>Album</td><td><?php echo $songinfo[0]['album']; ?></td>
</tr>
<tr>
<td>Duration</td><td><?php echo convertTime($songinfo[0]['duration']); ?></td>
<tr>
<td>Last Played</td><td><?php echo $songinfo[0]['date_played']; ?></td>
</tr>
</tr>
</table>
<?php if($error==0 && $requests==true) { ?>
<table class="bordered">
<tr><th scope="col" colspan="2">Track Request</th></tr>
<tr><td>
<?php
			echo "<div class=\"requestcontainer\" id=\"request\">\n";
			
			echo "	<form id=\"formrequest\" name=\"formrequest\" method=\"post\" action=\"$targetpage?page=$page$srchpath\">\n";
			echo "			<table align=\"center\" width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\">\n";
			echo "				<tr>\n";
			echo "					<td colspan=\"2\"><div align=\"center\" id=\"requestm\"><p></p></div></td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td>Name</td>\n";
			echo "					<td><input type=\"text\" name=\"requsername\" /></td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td valign=\"top\">Message</td>\n";
			echo "					<td><textarea name=\"reqmessage\" cols=\"43\" rows=\"5\"></textarea><br><small>Note: Your dedication will show up on the \"Now playing\" page of the website as soon as your requested track is played. The DJ might also read your dedication over the air.</small>
</td>\n";
			echo "				</tr>\n";
			echo "				<tr>\n";
			echo "					<td colspan=\"2\"><div align=\"center\"><input type=\"Submit\" name=\"reqsubmit\" value=\"REQUEST NOW\" /></div></td>\n";
			echo "				</tr>\n";
			echo "			</table>\n";
			echo "			<INPUT TYPE=\"hidden\" name=\"songID\" value=\"$reqid\">\n";
			echo "		</form>\n";
			echo "	</div>\n";
	
?>
</tr>
</tr>
</table>
<script type="text/javascript">
$(document).ready(function() {
	// process the form
	$('form#formrequest').submit(function(event) {

		// get the form data
		// there are many ways to get this data using jQuery (you can use the class or id also)
		dataString = $("#formrequest").serialize();


		// process the form
		$.ajax({
			type 		: 'POST', // define the type of HTTP verb we want to use (POST for our form)
			url 		: 'request.php', // the url where we want to POST
			data		: dataString, // our data object
			dataType 	: 'html', // what type of data do we expect back from the server
            encode          : true
		})
			// using the done promise callback
			.done(function(data) {

				// log data to the console so we can see
				if(data == 1) {
				$('#request').html("Request done");
				}
				else {
				$('#requestm').html(data);
				}
				// here we will handle errors and validation messages
			});

		// stop the form from submitting the normal way and refreshing the page
		event.preventDefault();
	});

});
</script>
<?php
}
elseif($error) {
		switch ($error) {
		case 3:
			echo "The selected track is already requested.<br />Please try again later, or select another track!";
			break;
		case 4:
			echo "Sorry, but you've reached the request limit for one day.";
			break;
		case 5:
			echo "The selected track is already in queuelist.";
			break;
	}
}
}
//REQUEST HISTORY
$reqhistory = db_select("SELECT * FROM requests WHERE SongID='{$reqid}' ORDER BY ID DESC LIMIT 10");
echo '<table class="bordered"><tr><th scope="col" colspan="3">Request History</th></tr>';
foreach($reqhistory as $row) {
		echo "<tr>    
			  <td width=\"120\">".$row['requested']."</td>
			  <td width=\"120\">".utf8_encode($row['username'])."</td>
			  <td>".utf8_encode($row['message'])."</td>
			  </tr>";
}
if(!$reqhistory) echo '<tr><td scope="col" colspan="3">This song is never requested yet.</td></tr>';

echo '</table></div>';
}
if(isset($_POST['songID'])){

	if($_POST['songID'] != "") {
	$reqid = db_quote($_POST['songID']);
	$reqname = db_quote($_POST['requsername']);
	$reqmsg = db_quote($_POST['reqmessage']);
	if(!$reqname){$error = 1;}
	if(!$reqid){$error = 2;}
			//track is already requested?
			$recheck = db_select("SELECT COUNT(*) AS num FROM `requests` WHERE `songID`='$reqid' AND `played`=0");
			if($recheck[0]['num'] > 0){
				$error = 3;
			}
			$recheck = db_select("SELECT COUNT(*) AS num FROM `queuelist` WHERE `songID`='$reqid'");
			if($recheck[0]['num'] > 0){
				$error = 5;
			}
			if($error == 0){
			//user has reached the request limit?
			$recheck = db_select("SELECT COUNT(*) AS num FROM `requests` WHERE `userIP`='$reqIP' AND DATE(`requested`) = DATE(NOW())");
			
			if($recheck[0]['num'] >= $reqLimit){
				$error = 4;
				$reccount = $recheck[0]['num'];
			}
		}
	
		switch ($error) {
		case 0:
			$resultx = db_query("INSERT INTO `requests` SET `songID`='$reqid', `username`='$reqname', `userIP`='$reqIP', `message`='$reqmsg', `requested`=now()");
			if($resultx > 0) {
				echo "1";
			} else {
				echo "DB ERROR";
			}

			
			break;
		case 1:
			echo "Please enter your name in order to send the request!";
			break;
		case 2:
			echo "Please select a track in order to send the request!";
			break;
		case 3:
			echo "The selected track is already requested.<br />Please try again later, or select another track!";
			break;
		case 4:
			echo "Sorry, but you've reached the request limit for one day.";
			break;
		case 5:
			echo "The selected track is already in queuelist.";
			break;
	}
	}
}
elseif($_POST) {
	echo "0";
}

?>
