<?php
include('_header.php');
?>

<div class="container-main">
<?php if($shoutcast) { ?>
<center><script>
            var flashvars = {};flashvars.serverHost = "<?php echo $shoutcast; ?>/;";flashvars.getStats = "1";flashvars.autoStart = "0";flashvars.textColour = "000000";flashvars.buttonColour = "000000";var params = {};params.bgcolor= "dce9f9";
        </script>
<script type="text/javascript" src="http://mixstreamflashplayer.net/v1.3.js"></script>
</center>
<?php } ?>

<div id="data"></div>
</div>

<script type="text/javascript">
$(function() {
    getStatus();
 
});
 
function getStatus() {
 
    $.ajax({
			type: "GET",
			url: "live.php",
			data: { },
			//dataType: "json",
			timeout: 5000, // in milliseconds
			success: function(data) {
				$('#data').html(data);
			},
			error: function(x, t, m) {
				if(t==="timeout") {
					alert("Kan data niet openen.");
				} else {
					//alert(t);
				}
			}
		});
}
 
</script>
<?php
include('_footer.php');
?>