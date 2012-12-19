<?php
session_start();
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <title>Step 2-Process</title>
    <link href='./pages/bootstrap-1.2.0.css' rel='stylesheet'>
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src='http://html5shim.googlecode.com/svn/trunk/html5.js'></script>
    <![endif]-->

    <!--[if IE]>
      <script type='text/javascript'>document.createElement('h7');</script>
      <script type='text/javascript'>document.createElement('h8');</script>
      <script type='text/javascript'>document.createElement('h9');</script>
      <script type='text/javascript'>document.createElement('h10');</script>
      <script type='text/javascript'>document.createElement('h11');</script>
      <script type='text/javascript'>document.createElement('h12');</script>
      <script type='text/javascript'>document.createElement('h13');</script>
      <script type='text/javascript'>document.createElement('h14');</script>
      <script type='text/javascript'>document.createElement('h15');</script>
      <script type='text/javascript'>document.createElement('h16');</script>
      <script type='text/javascript'>document.createElement('h17');</script>
    <![endif]-->

 	<SCRIPT type='text/javascript'>
	window.history.forward();     
	function noBack() 
		{ window.history.forward(); } 
	</SCRIPT> 

</head>
  
<body onload='noBack();' onpageshow='if (event.persisted) noBack();' onunload=''> 

	<div class='topbar'>
		<div class='fill'>
			<div class='container'>
				<h3><a href='#' ><tt><strong>ArticleNet</strong></tt></a></h3>
				<div style='position:absolute;left:720px;top:88px'><h18>Version1.2. &nbsp Last modified: 12/19/2012.</h18></div>
				<img src='./pages/img/logo.png' alt='logo' class='logo'>
			</div>
		</div>
	</div>
    
    
	<div class='container'>
		<div>
			<ul class='tabs'>
				<li><a href='index.html'>Home</a></li>
				<li><a href='instruction.html'>Learn about us</a></li>
				<li><a href='agreement.php'>Run Your Own SR</a></li>
				<li class='active'><a href='step1.php'>Evaluate DERP Models</a></li>
			</ul>
		</div>
     
		<div class='hero-unit2'>
     ";

if(!isset($_GET["topic"])){
	echo "<h16>Sorry we could not get your topic, please select it first. </h16><br>";
	echo "<a href='index.html'><h15>Please Click here to Homepage</h15></a>";
	exit;
	}
	
$topic=$_GET["topic"];
$old_base = "/var/www/cgi-bin/SR/SR-C/system/";
$dir_base=$old_base . $topic . "/";
$_SESSION["topic"]=$topic;
$_SESSION["dir"]=$dir_base;
echo "<h4>We have received your request.</h4>";

//ssh to account
if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
if(!($con = ssh2_connect("netlab.bmi.osumc.edu", 22))){
    echo "fail: unable to establish connection\n";
} else {
    if(!ssh2_auth_password($con, "ji02", "jxn2025133")) {
        echo "fail: unable to authenticate\n";
    } else {
    }
}	   
	   
// get the numbet of articles
$PATH=$dir_base;
$num=sizeof(scandir($PATH));
$num=($num>2)?$num-2:0;
$articlenum = $num -11;
echo "<h4>There are " . $articlenum. " articles in total.</h4>";
echo "<hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";

echo "  <table class='table1'>
			<tr>
			<td width='10%'>
			<a href='model2.php'><input type='button' name='next' class='btn primary' value='Continue' /></a></td>
			</tr> 
		</table> ";
		 
  echo " </div>
         </div>
	</body>
</html>";
  
?>