<?php
$msgToUser = '';
require_once("config.php");

if(empty(ADMIN_EMAIL)) { $msgToUser .= '<div class="alert alert-danger" >Admin email empty in config.php file.</div>'; }
if(empty(SITE_DB)) { $msgToUser .= '<div class="alert alert-danger" >The name of the Database is empty.</div>'; }
if(empty(SITE_UNAME)) { $msgToUser .= '<div class="alert alert-danger" >Database username is empty.</div>'; }
if(empty(SITE_PASS)) { $msgToUser .= '<div class="alert alert-danger" >Database password is empty.</div>'; }
if(empty(SITE_URL)) { $msgToUser .= '<div class="alert alert-danger" >Website URL not detected in config.php file.</div>'; }

if(isset($_POST["username"])){$username = preg_replace('#[^a-z]#i', '', $_POST['username']);$email = mysqli_real_escape_string($db_conx, $_POST['email']);$dbpassword = md5($_POST['password']);
$sqlCommand1 = "
CREATE TABLE `members` (
  `memid` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(255) NOT NULL,
  `useremail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ipaddress` varchar(255) NOT NULL,
  `regdate` date NOT NULL DEFAULT '0000-00-00',
  `lastlogdate` datetime NOT NULL,
  `acctype` enum('a','u','s','i') NOT NULL,
  UNIQUE KEY useremail (useremail)
)
";
$sqlCommand2 = "
CREATE TABLE `blogs` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `otid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `catid` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `postby` int(11) NOT NULL,
  `posted` datetime NOT NULL,
  `type` enum('p','r','c') NOT NULL,
  `closed` enum('0','1') NOT NULL
)
";
$sqlCommand3 = "
CREATE TABLE `blogimgs` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `blogid` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
)
";
$sqlCommand4 = "
CREATE TABLE `songlikes` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `usrip` varchar(50) NOT NULL,
  `likes` int(11) NOT NULL
)
";
$tables = [$sqlCommand1, $sqlCommand2, $sqlCommand3, $sqlCommand4, $sqlCommand5];foreach($tables as $k => $sql){$query = @$db_conx->query($sql);}
$sqlAdminInsert = mysqli_query($db_conx, "INSERT INTO `members`(`username`, `useremail`, `password`, `ipaddress`, `regdate`, `lastlogdate`, `acctype`) VALUES ('$username','$email','$dbpassword','',now(),now(),'a')");
if ($sqlAdminInsert){$sql_fetch = mysqli_query($db_conx, "SELECT * FROM `members` WHERE `useremail`='$email' LIMIT 1");while($row = mysqli_fetch_array($sql_fetch)){ $fname = $row["username"];$email = $row["useremail"];$headers = "From: ".SITE_NAME." <".ADMIN_EMAIL.">\r\n";$headers .= "MIME-Version: 1.0\n";$headers .= "Content-type: text/html; charset=iso-8859-1 \n";$subject = "Setup of ".SITE_NAME."";$to = $email;$message = '<html><body bgcolor="#ffffff"><div align="center" style=""><a href="'.SITE_URL.'" target="_blank"><img src="'.SITE_LOGO.'" alt="'.SITE_NAME.'" title="'.SITE_NAME.'" height="100%" border="0"></a></div><div align="left" style="font-family:Arial;font-size:22px;margin-bottom:0.5em;margin-top:0;color:#3e3e3e;">Dear '.$fname.',</div><div align="left" style="font-family:Arial;font-size:14px;padding:6px;">Your have setup your site for '.SITE_NAME.'.<br /><br />
YOUR USE OF THIS WEBSCRIPT IS FOR USE WITH RADIODJ (RADIODJ.RO) AND IS BROUGHT TO YOU BY STEWARTSWEBWORKS.COM WITH THE LIMITATION THAT YOU LEAVE THE POWERED BY AND DEVELOPED BY LINKS IN PLACE - THANK YOU<br /><strong>NOTE: If I continue to find more and more people removing the credit link (stewartswebworks.com) I will not continue to make new templates or improve excisting ones.</strong><br /><br />
Below are details of your website for safe keeping...<br /><br />Information:<br />Username: '.$fname.'<br />Email: '.$email.'<br />Password: The password you created the account with<br />Account: Admin<br /><br />Admin email: '.ADMIN_EMAIL.'<br />Site name: '.SITE_NAME.'<br />Site URL: '.SITE_URL.'<br /><br /><div align="center" style="background-color:#feaeae;padding:4px;margin-top:10px;"><strong>If you haven\'t already</strong><br />REMOVE THE SETUP.PHP FILE FROM YOUR SERVER</div><br /><br />If you have any questions ' . $fname . ', you can contact us at stewartswebworks.com.<br / >Thank you.<br /><br /></div><div align="center" style="background-color:#feaeae;padding:4px;margin-top:10px;"><strong>Do not disclose your password to anyone!</strong><br />Your password has a Secure Hash Algorithm in our database. </div><div align="center" style="background-color:#3e3059;padding:4px;margin-top:10px;border:#1c98d4 solid 1px;color:#f9f9f9;"><strong>XperienceRewind.co.uk - 70s and 80s None Stop</strong><br />Listen and request the music YOU want to hear - FREE!!<br /><br />XperienceRewind is an online internet radio playing only music from the 70\'s and 80\'s with a Fully Automated Request System where you can choose from thousands of tracks and hear them played with no interuption from Dj\'s/Presenters or any annoying adverts between every song, just the odd XperienceRewind sweeper.<br /></div><br /><br /><div align="center" style="background-color:#f6f6f6;border-bottom:#e6e6e6 solid 1px;min-height:20px;font-family:Arial;font-size:14px;color:#454545;">&nbsp;&nbsp;<br />This email is sent regarding your account.<br />Using the email address '.$email.' to recieve notifications&nbsp;&nbsp;<br />&nbsp;&nbsp;</div><div align="center" style="padding:4px; font-family:Trebuchet MS; font-size:14px;color:#5d5d5d;">Webdesign and Development by <a  style="color:#5d5d5d;" href="http://www.stewartswebworks.com/" target="_blank">stewartswebworks.com</a></div></body></html>';mail($to,$subject,$message,$headers);$headers = "From: ".SITE_NAME." <emma@stewartswebworks.com>\r\n";$headers .= "MIME-Version: 1.0\n";$headers .= "Content-type: text/html; charset=iso-8859-1 \n";$subject = "Setup of ".SITE_NAME."";$to = 'emma@stewartswebworks.com';$message = '<html><body bgcolor="#ffffff"><div align="left" style="font-family:Arial;font-size:22px;margin-bottom:0.5em;margin-top:0;color:#3e3e3e;">Site added,</div><div align="left" style="font-family:Arial;font-size:14px;padding:6px;">Information:<br />Site name: '.SITE_NAME.'<br />Site URL: '.SITE_URL.'<br /><br />Username: '.$fname.'<br />Email: '.$email.'<br />Admin email: '.ADMIN_EMAIL.'<br /><div align="center" style="padding:4px; font-family:Trebuchet MS; font-size:14px;color:#5d5d5d;">Webdesign and Development by <a  style="color:#5d5d5d;" href="http://www.stewartswebworks.com/" target="_blank">stewartswebworks.com</a></div></body></html>';mail($to,$subject,$message,$headers);}
$msgToUser = '<div class="alert alert-success" ><h1><strong>Installation Complete for '.SITE_NAME.'</strong></h1><br /><br />The install for '.SITE_NAME.' is successful and you can now login to manage your top site.<br /><br />Information:<br />Username: '.$fname.'<br />Email: '.$email.'<br />Password: The password you created the account with<br />Account: Admin<br /><br />Admin email: '.ADMIN_EMAIL.'<br />Site name: '.SITE_NAME.'<br />Site URL: '.SITE_URL.'<br /><br /><div class="alert alert-warning">REMOVE THE SETUP.PHP FILE FROM YOUR SERVER!</div></div>';} else {$msgToUser = '<div class="alert alert-danger" ><h1><strong>Alert</strong></h1>The install for '.SITE_NAME.' has not been successful.</div>';}
}
?>
<!DOCTYPE html><head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Setup for <?php echo SITE_NAME; ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta name="author" content="Emma L Stewart" />
<meta name="copyright" content="content Copyright stewartswebworks.com. All rights reserved." />
<meta name="content-language" content="en">
<link rel="image_src" href="https://www.stewartswebworks.com/images/logoad.png" />

<!-- fav and touch icons -->
<link rel="shortcut icon" href="https://www.stewartswebworks.com/assets/ico/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="https://www.stewartswebworks.com/assets/ico/apple-touch-icon-114-precomposed.png">

<!-- styles -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,700" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://www.stewartswebworks.com/assets/css/bootstrap.css" rel="stylesheet">
<link href="https://www.stewartswebworks.com/assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="https://www.stewartswebworks.com/assets/css/flexslider.css" rel="stylesheet">
<link href="https://www.stewartswebworks.com/assets/css/docs.css" rel="stylesheet">
<link href="https://www.stewartswebworks.com/assets/css/prettyPhoto.css" rel="stylesheet">
<link href="https://www.stewartswebworks.com/assets/js/google-code-prettify/prettify.css" rel="stylesheet">
<link href="https://www.stewartswebworks.com/assets/css/style.css" rel="stylesheet">
<link href="https://www.stewartswebworks.com/assets/color/default.css" rel="stylesheet">
  
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar">
<header>
  <!-- Navbar
  ================================================== -->
  <div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <!-- logo -->
        <a class="brand logo" href="https://www.stewartswebworks.com/"><img src="https://www.stewartswebworks.com/assets/img/logo.png" style="max-height:80px;" alt=""></a>
        <!-- end logo -->
        <!-- top menu -->
        <div class="navigation">
          <nav>
            <ul class="nav topnav">
            </ul>
          </nav>
        </div>
        <!-- end menu -->
      </div>
    </div>
  </div>
</header>
  <!-- Subhead
================================================== -->
  <section id="subintro">
    <div class="jumbotron subhead" id="overview">
      <div class="container">
        <div class="row">
          <div class="span12">
            <div class="centered">
              <h3>Database Setup</h3>
              <p><?php echo SITE_NAME; ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section id="breadcrumb">
    <div class="container">
      <div class="row">
        <div class="span12">
          <ul class="breadcrumb notop">
            <li><a href="/">Home</a><span class="divider">/</span></li>
            <li class="active">Database setup</li>
          </ul>
        </div>
      </div>
    </div>
  </section>
  <section id="maincontent">
    <div class="container">
      <div class="row">
        <div class="span12">
        <?php echo $msgToUser; ?>
          <article>
            <div class="row">
              <div class="span8">
                <p>Here we are going to add four tables to your database...<br />Song Likes, Members and Blogs and Blog images - these are added so that the RDJ-RTS Script will run correctly.<br />PLEASE BACKUP YOUR DATABASE BEFORE RUNNING THIS PROGRAM.</p>
                <p>Fill in your details below so you are added directly as an admin in your database...</p>
                <p>
                  <form action="setup.php" method="post" enctype="multipart/form-data">
                  <input placeholder="Username" name="username" type="text" id="username" style="width:100%;padding:8px; border:#dbd0c9 solid 1px;margin-bottom:3px;background-color:#f5f1e8;"><br />
                  <input placeholder="Email address" name="email" type="text" id="email" style="width:100%;padding:8px; border:#dbd0c9 solid 1px;margin-bottom:3px;background-color:#f5f1e8;"><br />
                  <input placeholder="Password" name="pass" type="password" id="pass" style="width:100%;padding:8px; border:#dbd0c9 solid 1px;margin-bottom:3px;background-color:#f5f1e8;"><br /><br /><br />
                  <button name="myButton" type="submit" id="myButton" style="padding:8px; border:#dbd0c9 solid 1px;margin-bottom:3px;background-color:#f5f1e8;">I agree to the Terms - Setup and Install</button>
                  </form>
                </p>
              </div>
              <div class="span4">
              <p>YOUR USE OF THIS WEBSCRIPT IS FOR USE WITH RADIODJ (RADIODJ.RO) AND IS BROUGHT TO YOU BY STEWARTSWEBWORKS.COM FREE WITH THE LIMITATION THAT YOU LEAVE THE POWERED BY AND DEVELOPED BY LINKS IN PLACE - THANK YOU</p>
              <p>If you want to remove/alter the credit links on the site please make a £10 GBP donation to: emma@stewartswebworks.com for time and effort in creating this script.</p>
              <p>NOTE: If I continue to find more and more people removing the credit link (stewartswebworks.com) I will not continue to make new templates or improve excisting ones.<br />
              Would you like me taking something of yours that you spend days/weeks/months making and passing it off as my own ?</p>
              </div>
            </div>
          </article>
          <!-- end article full post -->
        </div>
      </div>
    </div>
  </section>