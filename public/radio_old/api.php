<?php

date_default_timezone_set('America/Chicago'); 		//Change this to your time/zone
$datafile = "data.txt";								//How is named the txt file?
$lines2display = 3; //How many entries to display?
$pswd = "5876414213";									//The password set in rdj options

if ((isset($_POST["xpwd"])) && (isset($_POST["title"]))) {
   $xpwd= stripcslashes($_POST["xpwd"]);
   if ($xpwd == $pswd) { //please change the password here and in now playing info plugin!
      $data = stripcslashes($_POST["title"]);

      $Handle = fopen($datafile, 'w');
      fwrite($Handle, date('H:i') . " " . $data . "\n");
      fclose($Handle);
   }
} Else {
?>

<!DOCTYPE html>
<html>
  <head>

    <title>Recently Played Tracks</title>
  </head>
  <body>
	<div id="main">
    <?php
        $str= file_get_contents($datafile);
		$str = nl2br($str, true); // for XHMTL (in other words <br />). Use false for <br>. i.e $str = nl2br($str, false);
		echo $str;
    ?>
    </div>
  </body>
</html>

<?php
}
?>