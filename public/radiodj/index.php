<?php
require_once("config.php");
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
        <li><a href="/">Home</a></li>
        <li><a href="topsongs.php">Top Songs</a></li>
        <li><a href="requests.php">Requests</a></li>
        <li><a href="recently.php">Recently</a></li>
        <?php echo TUNEIN; ?>
          </ul>
        </div>
        <div class="header_top_right">
          <a href="/" class="logo"><img src="<?php echo SITE_LOGO; ?>" width="100%" style=" max-width:230px;max-height:56px;" alt="<?php echo SITE_NAME; ?>"></a>
        </div>
      </div>
    </div>
  </div>
</header>

<?php require_once("assets/nowplaying.php"); ?>

<?php echo FOOTER; ?>
</div>
<!-- YOUR USE OF THIS WEBSCRIPT IS FOR USE WITH RADIODJ (RADIODJ.RO) AND IS BROUGHT TO YOU BY STEWARTSWEBWORKS.COM FREE WITH THE LIMITATION THAT YOU LEAVE THE POWERED BY AND DEVELOPED BY LINKS IN PLACE - THANK YOU -->
</body>
</html>
