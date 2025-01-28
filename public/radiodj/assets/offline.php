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
          </ul>
        </div>
        <div class="header_top_right">
          <a href="/" class="logo"><img src="<?php echo SITE_LOGO; ?>" width="100%" style=" max-width:230px;max-height:56px;" alt="<?php echo SITE_NAME; ?>"></a>
        </div>
      </div>
    </div>
  </div>
</header>

<div align="center">    
  <h3><?php echo SITE_NAME; ?> - <?php echo SITE_SLOG; ?></h3>
  <br>
    <h2>We Are Sorry</h2>
    <h1 style="font-size:90px;">OFF-AIR</h1>
    <h3>Unfortunately, <?php echo SITE_NAME; ?> is offline at the moment, please check back soon!</h3>
  <div style="border-bottom:#19242a solid 4px;margin-top:30px;"></div>
  <h3>Alternatively</h3>
  <p><a href="https://www.xperiencerewind.co.uk/" title="XperienceRewind.co.uk" target="_blank"><img src="https://www.xperiencerewind.co.uk/images/listento.png" width="690" height="85" border="0"></a></p>
  <h4>70s and 80s Internet Radio <a href="https://www.xperiencerewind.co.uk/" title="XperienceRewind.co.uk" target="_blank">XperienceRewind.co.uk</a></h4>
</div>

<footer id="footer">
  <p>&copy; <?php echo SITE_NAME; ?> <?php echo date("Y"); ?> All rights reserved. <?php if(!empty(ORS_LNUMB)){ echo '(Licence No:'.ORS_LNUMB.')'; }else{ echo ''; } ?><br /> POWERED BY <a style="color:#d2d2d2;" href="https://radiodj.ro" target="_blank">RADIODJ</a> - Website Developed By  <a style="color:#d2d2d2;" href="https://stewartswebworks.com" target="_blank">StewartsWebWorks.com</a></p>
</footer>
</div>
<!-- YOUR USE OF THIS WEBSCRIPT IS FOR USE WITH RADIODJ (RADIODJ.RO) AND IS BROUGHT TO YOU BY STEWARTSWEBWORKS.COM FREE WITH THE LIMITATION THAT YOU LEAVE THE POWERED BY AND DEVELOPED BY LINKS IN PLACE - THANK YOU -->
</body>
</html>