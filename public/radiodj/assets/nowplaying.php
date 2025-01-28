<script type="text/javascript">
function Ajax(){
var xmlHttp;
try{ 
xmlHttp=new XMLHttpRequest();// Firefox, Opera 8.0+, Safari
}catch (e){
try{
xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
}catch (e){
try{
xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
}catch (e){
alert("Your browser does not support this webpage sorry...");
return false;
}
}
}
xmlHttp.open( "GET", "playing.php" );
xmlHttp.onreadystatechange=function()
{
	if(xmlHttp.readyState == 4)
	{
		document.getElementById('ReloadThis').innerHTML=xmlHttp.responseText;
	}
}
xmlHttp.setRequestHeader( "If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT" );
xmlHttp.send( null );
}
window.onload=function(){
setInterval('Ajax()', 40000 );
}
</script>
<div id="ReloadThis"><?php include("playing.php"); ?></div>