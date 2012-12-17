<?php
session_start();

echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <title>Step 3-Recommend</title>
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
			<div style='position:absolute;left:720px;top:88px'><h18>Version1.2. &nbsp Last modified: 11/30/2012.</h18></div>
			<img src='./pages/img/logo.png' alt='logo' class='logo'>
			</div>
		</div>
	</div>

	<div class='container'>
		<div>
			<ul class='tabs'>
			<li><a href='index.html'>Home</a></li>
			<li><a href='instruction.html'>Learn about us</a></li>
			<li  class='active'><a href='u-step1.php'>Run Your Own SR</a></li>
			<li><a href='step1.php'>Evaluate DERP Models</a></li>
			</ul>
		</div>
		<div class='hero-unit2'>
     ";

if( !isset($_SESSION["tag"]) ||  !isset($_SESSION["key"]) || !isset($_SESSION["dir"]) || !isset($_SESSION["recommended"]) ){
echo "<h16>We do not have your workspace/session, please start over. </h16><br>";
echo "<a href='index.html'><h15>Please Click here to HomePage</h15></a>";
exit;
}	
 	 	 
$dir_base=$_SESSION["dir"];
$articlenum = $_SESSION["articlenum"];
$time = $_SESSION["time"];	 
$step = $_SESSION["step"];
//$recommended = $_SESSION["recommended"];

$_SESSION["tag"]="3";

//echo "Session tag is" .$_SESSION["tag"] . "<br>";
//echo "Session key is" .$_SESSION["key"] . "<br>";
//echo "POST key is" .$_POST["key1"] . "<br>";

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

// comes from u-feedback, and this is not a refresh, put feedback into II-list and EE-list
if(isset($_POST['next']) && $_POST['key1'] == $_SESSION['key1']){
//echo "POST key1 is" .$_POST["key1"] . "<br>";
for($i=0;$i<=$step;$i++){
	$radios = "step" . $i;
	if (isset($_POST[$radios])){
		if(strlen($_POST[$radios])<=4  && $_POST[$radios]!="clear"){
				$cmd = "echo ". $_POST[$radios].  " >> " . $dir_base . "II.txt";
				if (!($stream = ssh2_exec($con, $cmd))) {
					echo "fail: unable to execute command\n";
				} 
				else {
					// collect returning data from command
					stream_set_blocking($stream, true);
					$data = "";
					while ($buf = fread($stream,4096)) {
						$data .= $buf;
					}
					fclose($stream);
				}
		}
		if(strlen($_POST[$radios])>4  && $_POST[$radios]!="clear"){
				$cmd = "echo ". $_POST[$radios].  " >> " . $dir_base . "EE.txt";
				if (!($stream = ssh2_exec($con, $cmd))) {
					echo "fail: unable to execute command\n";
				} 
				else {
					// collect returning data from command
					stream_set_blocking($stream, true);
					$data = "";
					while ($buf = fread($stream,4096)) {
						$data .= $buf;
					}
					fclose($stream);
				}
		}
	}
}
$_SESSION["recommended"]=$_SESSION["recommended"]+$step;;
$_SESSION['key1'] = mt_rand(1, 1000);
echo "Inclusion-list and Exclusion-list has been updated!";
}
else{
echo 'This is a refresh, You have already sumbitted your feedbacks.';
}

$recommended = $_SESSION["recommended"];
echo "<h13><br>Summary Report:</h13><br>";

// call result
       echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";
		$args= $dir_base ." " . $articlenum ." " . $time ." " . $step . " " . $recommended;
		if($args == "")
		echo "<h1>You did not enter any arguments.</h1>";
		else
		{
			$command = "/var/www/cgi-bin/SR/SR-C/result " . escapeshellcmd($args);
			passthru($command,$res);
		}

echo "<hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> "; 
echo "<a href='u-feedback.php'><input type='button' name='back' class='btn primary' value='Back to Recommendation' /></a>";
echo "       ";
echo "<a href='u-readjust.php'><input type='button' name='back' class='btn info' value='Adjust Parameters' /></a>";
echo "<h13><br><br><br>If you have completely finished, </h13><br>";

// link to pubmed	
       //echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";
		$args= $dir_base;
		if($args == "")
		echo "<h1>You did not enter any arguments.</h1>";
		else
		{
			$command = "/var/www/cgi-bin/SR/SR-C/pubmed " . escapeshellcmd($args);
			passthru($command,$res);
		}
		 
 echo " </div>
	</div>
	<a href='#' id='backtotop'><img src='./pages/img/top.png' alt='back to top' /></a>  
	</body>
</html>"; 
?>