<?php
include('_header.php');
$letters = "A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z|^";
$alphabetic  = explode("|", $letters);

//search
if(isset($_GET['search'])){
	if($_GET['search'] != "") {
		$srch = db_quote($_GET['search']);
		$srchpath = "search=$srch&";
		$srcquery = "AND (`artist` LIKE '%$srch%' OR `title` LIKE '%$srch%')"; //Search artist and title
	}
}
if(isset($_GET['letter'])){

		if($_GET['letter'] == "^")
		{
			$letter = db_quote($_GET['letter']);
			$srchpath = "letter=$letter&";
			$srcquery .= "AND (artist REGEXP '^[0-9]+')";
		}
		elseif($_GET['letter'] != "") {
	
			$letter = db_quote($_GET['letter']);
			$srchpath = "letter=$letter&";
			$srcquery = "AND artist LIKE '$letter%'"; //Search letter
		}
}
$count = db_query("SELECT COUNT(ID) AS count FROM songs WHERE `enabled`='1' $srcquery AND `song_type`=0");
$rows = mysqli_fetch_assoc($count);
$count = $rows['count'];
$total_pages = ceil($count / $num_rec_per_page); 


foreach($alphabetic as $row) {
	if($row == $letter) {
		if($row == "^")
			$alph .= '<a style="color:red;" href="list.php?letter='.$row.'">#</a> | ';
		else
			$alph .= '<a style="color:red;" href="list.php?letter='.$row.'">'.$row.'</a> | ';
	}
	else {
		if($row == "^")
			$alph .= '<a href="list.php?letter='.$row.'">#</a> | ';
		else
			$alph .= '<a href="list.php?letter='.$row.'">'.$row.'</a> | ';
	}
}
?>


<div class="container-main">
<table class="bordered">
<tr>
<th>Search by Artist:  | <?php echo $alph; ?></th>
</tr>
</table>
<table class="bordered">
<tr>
<th><form name="input" action="list.php" method="get">Search artist or title: <input type="text" value="<?php echo $srch; ?>" name="search"> <input type="submit" value="Search"></form></th>
</tr>
</table>
<table class="bordered">
<?php
//pagination
$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = 100;
$startpoint = ($page * $limit) - $limit;
//query
$nextquery = db_select("SELECT * FROM songs WHERE `enabled`='1' $srcquery AND `song_type`=0 LIMIT {$startpoint}, {$limit}");
echo '<tr><th scope="col" colspan="3">'.pagination($count,$limit,$page,$url = '?'.$srchpath.'').'</th></tr>';	 

echo '<tr><th scope="col" colspan="2">Results</th><th>time</th></tr>';	  
if($nextquery) {
	foreach($nextquery as $row) {
		echo "<tr>    
			  <td><a href=\"#\" onclick=\"openDialog('Song Info','request.php?song=".$row['ID']."&req=off', 'Song Info');return false;\">".utf8_encode($row['artist'])." - ".utf8_encode($row['title'])."</a></td>
			   <td><a href=\"\" onclick=\"openDialog('Request','request.php?song=".$row['ID']."', 'Request');return false;\">Request</a></td>
			  <td>".convertTime($row['duration'])."</td>
			  </tr>";
	}
}
else {
echo '<tr><td scope="col" colspan="3">No results found!</td></tr>';	  
}
echo '<tr><th scope="col" colspan="3">'.pagination($count,$limit,$page,$url = '?'.$srchpath.'').'</th></tr>';	 
?>
</table>
<div class="clear"></div>
</div>

<?php
include('_footer.php');
?>
