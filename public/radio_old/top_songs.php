<?php
include('_header.php');
?>

<div class="container-main">
<table class="bordered">
<?php
$nextquery = db_select("SELECT `artist`, title, count(*) AS tracks FROM `history` WHERE TIMESTAMPDIFF(DAY, `date_played`, NOW()) <= " . $resDays . " AND `song_type`=0 GROUP BY title ORDER BY tracks DESC LIMIT 0," . $resLimit);

echo '<tr><th scope="col" colspan="2">Top Songs</th><th width="60">Count</th></tr>';	  
if($nextquery) {
$inc = 1;
	foreach($nextquery as $row) {
		echo "<tr>
			  <td>" . $inc . "</td>        
			  <td>".utf8_encode($row['artist'])." - ".utf8_encode($row['title'])."</td>
			  <td width='60'>".$row['tracks']."</td>
			  </tr>";
		$inc += 1;
	}
}
else {
echo '<td scope="col" colspan="3">No results found!</td>';	  
}
?>

</div>

<?php
include('_footer.php');
?>