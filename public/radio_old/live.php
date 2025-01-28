<?php
include('inc/config.php');
$path = "";
?>
<table class="bordered">
<?php

//queries
$nextquery = db_select("SELECT songs.ID, songs.artist, songs.title, songs.duration, songs.tdate_played, queuelist.songID, queuelist.ID FROM songs, queuelist WHERE songs.song_type=0 AND songs.ID=queuelist.songID ORDER BY queuelist.ID ASC LIMIT 0," . $nextLimit);
$history = db_select("SELECT songs.ID, songs.artist, songs.title, songs.duration, songs.date_played FROM songs WHERE songs.song_type=0 ORDER BY songs.date_played DESC LIMIT 0," . ($resLimit+1));

//time left to refresh
$time = strtotime(date('H:i:s'));
$now = strtotime(date('H:i:s', strtotime($history[0]['date_played'])));
$duration = convertTime($history[0]['duration']);
$seconds = strtotime("1970-01-01 $duration UTC");
$timer = $now + $seconds - $time;
$timer = $timer * 1000;
//Now Playing
		echo '<tr>
			   <th scope="col" colspan="3">Currently Playing</th>
			  </tr>';
if($timer) {
		echo '<tr>
			   <td scope="col" colspan="2"><a href="#" onclick="openDialog(\'Song Info\',\'request.php?song='.$history[0]['ID'].'&req=off\', \'Song Info\');return false;">'.utf8_encode($history[0]['artist']).' - '.utf8_encode($history[0]['title']).'</a></td>
			   <td style="text-align:center;" width="70">'.$duration.'</td>
			  </tr>';
}
else {
		echo '<tr>
			   <td scope="col" colspan="3">Nothing Playing.</td>
			  </tr>';
}  
//Coming up			  
		echo '	  <tr>
    <th scope="col" colspan="3">Coming up</th>
  </tr>';	
if($nextquery) {
	foreach($nextquery as $row) {
		echo "<tr>
			  <td scope=\"col\" colspan=\"2\">".$row['ID'].". <a href=\"#\" onclick=\"openDialog('Song Info','request.php?song=".$row['songID']."&req=off', 'Song Info');return false;\">".utf8_encode($row['artist'])." - ".utf8_encode($row['title'])."</a></td>

			  <td style=\"text-align:center;\">".convertTime($row['duration'])."</td>
			  </tr>";
	}
}
else {
		echo '<tr>
			   <td scope="col" colspan="3">Nothing Upcoming</td>
			  </tr>';
}
//Recently Played
		echo '<tr>
			   <th scope="col" colspan="3">Recently Played</th>
			  </tr>';
if($nextquery) {
	foreach(array_slice($history,1) as $row) {

		echo "<tr>
			  <td width=\"80\">".date('H:i:s', strtotime($row['date_played']))."</td>        
			  <td><a href=\"#\" onclick=\"openDialog('Song Info','request.php?song=".$row['ID']."&req=off', 'Song Info');return false;\">".utf8_encode($row['artist'])." - ".utf8_encode($row['title'])."</a></td>
			  <td style=\"text-align:center;\">".convertTime($row['duration'])."</td>
			  </tr>";
	}
}
else {
		echo '<tr>
			   <td scope="col" colspan="3">Nothing Playing</td>
			  </tr>';
}
?>

</table>
<?php
//Refresh timer in Milliseconds
if($timer) {
?>
<script type="text/javascript">
    setTimeout("getStatus()",<?php echo $timer+2000; ?>);
</script>
<?php
}
?>