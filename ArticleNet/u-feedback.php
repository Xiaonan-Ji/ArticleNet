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


	<script language='javascript' type='text/javascript'>
	function finish(){ 
		frm.action = 'u-result.php';
		} 
		
	function mysubmit() { 
		frm.action = 'u-feedback.php';
		} 

	function clearradio(radio) { 
		for (var i = 0; i < 2; i++) {
		document.getElementByName('radio')[i].checked = false;
		}
	} 
	</script> 

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
				<li class='active'><a href='u-step1.php'>Run Your Own SR</a></li>
				<li><a href='step1.php'>Evaluate DERP Models</a></li>
			</ul>
		</div>
       
		<div class='hero-unit2'>";
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

if( !isset($_SESSION["tag"]) ||  !isset($_SESSION["key"]) || !isset($_SESSION["dir"]) || !isset($_SESSION["recommended"]) ){
echo "<h16>We do not have your workspace/session, please start over. </h16><br>";
echo "<a href='index.html'><h15>Please Click here to HomePage</h15></a>";
exit;
}


$dir_base=$_SESSION["dir"];	
$articlenum = $_SESSION["articlenum"];	

$num = $_SESSION["begin"];
$key = $_SESSION["key"];
$type = $_SESSION["type"];
$ab = $_SESSION["ab"];
$mh = $_SESSION["mh"];
$title = $_SESSION["title"];
$author1 = $_SESSION["author1"];
$author2 = $_SESSION["author2"];
$step = $_SESSION["step"];

// if comes from weight setting, weight parameters are readjusted, update it
if($_SESSION["tag"]=="4"){
	echo "Weight parameters has been updated!<br>";
	$key = $_GET["key"];
	$type = $_GET["type"];
	$ab = $_GET["ab"];
	$mh = $_GET["mh"];
	$step = $_GET["step"];
	$title = $_GET["title"];
	$author1 = $_GET["author1"];
	$author2 = $_GET["author2"];

	$_SESSION["key"]=$key;
	$_SESSION["type"]=$type;
	$_SESSION["ab"]=$ab;
	$_SESSION["mh"]=$mh;
	$_SESSION["step"]=$step;
	$_SESSION["begin"]=$num;
	$_SESSION["title"]=$title;
	$_SESSION["author1"]=$author1;
	$_SESSION["author2"]=$author2;
	
	$time = $_SESSION["time"];
	$time1 = $_SESSION["time"];
	$time=$time+1;
	$_SESSION["time"]=$time;

	if(!($_GET["step"]>0 && $_GET["step"]<$articlenum)){
		echo "<h16>Please set the number of recommended articles per round, and it should be no less than 1 and no larger than the size of input article list. </h16><br>";
		echo "<a href='u-readjust.php'><h15>Please Click here to the previous step</h15></a>";
		exit;
	}
	echo "<h5>The weight parameter have been adjusted to:</h5>";
	echo  "<h4>Title Similarity: " . $title . "</br>Abstract Similarity: ".$ab."</br>MeSH Similarity: ".$mh."</br>Author Similarity: " . $author1 . "</br>Keywords: ".$_GET["key"]."</br>Publish Type: ".$type."</br>Author: " . $author2 ."</h4>";
}

// if judgments are given, put feedback into II-list and EE-list (page comes from u-feedback or u-recommend, exclude refresh)
if($_SESSION["tag"]=="1" || $_SESSION["tag"]=="2"){
	//echo "Session key1 is" .$_SESSION["key1"] . "<br>";
	//if this is a refresh, do not re-submit the form
	if(isset($_POST['submit']) && $_POST['key1'] == $_SESSION['key1']){
		//echo "POST key1 is" .$_POST["key1"] . "<br>";
		//echo "Feedback is updated!";
		for($i=0;$i<=$step;$i++){
		$radios = "step" . $i;
		if (isset($_POST[$radios])){
			if(strlen($_POST[$radios])<=4 && $_POST[$radios]!="clear"){
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
			if(strlen($_POST[$radios])>4 && $_POST[$radios]!="clear"){
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
	echo "  Inclusion-list and Exclusion-list have been updated!\n";
	//update the session key
	$_SESSION['key1'] = mt_rand(1, 1000);
	$_SESSION["recommended"] = $_SESSION["recommended"]  + $step;
	
	$time = $_SESSION["time"];
	$time1 = $_SESSION["time"];
	$time=$time+1;
	$_SESSION["time"]=$time;
	}	
	else{
		echo 'This is a refresh, You have already sumbitted your feedbacks.';
		$time1 = $_SESSION["time"];
	}
}

if($_SESSION["tag"]=="3"){
	echo "OK, we are back to recommendation!";
	$time = $_SESSION["time"];
	$time1 = $_SESSION["time"];
	$time=$time+1;
	$_SESSION["time"]=$time;
}

$_SESSION["tag"]="2";
$recommended = $_SESSION["recommended"];	


// display the II-list and EE-list	
echo "<h5>The Articles you want are:</h5><h8>(System ID)&nbsp&nbsp</h8>";
//for($i=0;$i<count($II);$i++){
//echo $II[$i] . " ";}
$file1 =  $dir_base . "II.txt";
$handle = fopen ($file1, "r");
while (!feof ($handle)) {
    $buffer = fgets($handle, 4096);
    echo $buffer;
}
fclose ($handle);
echo "</br>";
echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";
echo "<h5>The Articles you do not want are:</h5><h8>(System ID)&nbsp&nbsp</h8>";
//for($i=0;$i<count($EE);$i++){
//echo $EE[$i] . " ";}

$file2 =  $dir_base . "EE.txt";
$handle = fopen ($file2, "r");
while (!feof ($handle)) {
	$buffer = fgets($handle, 4096);
	echo substr($buffer, 4);
}
fclose ($handle);
echo "</br>";


// call recommendation
echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";
echo "<form name='frm' method='post' action=' '> ";
$args=$step." " . $step." " . $articlenum . " " .  $dir_base ." 40" . " " . $key . " " . $type . " " . $ab . " " . $mh . " " . $title . " " . $author1 . " " . $author2 . " " . $time1 . " " . $recommended;
if($args == "")
	echo "<h1>You did not enter any arguments.</h1>";
else
	{
	echo "<h5>A Brief Summary Report: </h5>";
	$command = "/var/www/cgi-bin/SR/SR-C/evalue2 " . escapeshellcmd($args);
	passthru($command,$res);
	}
		
echo " <input type='hidden' name='key1' value='" . $_SESSION['key1'] . "' />";
echo " <table class='table1'>
		<tr>
		<td width='10%'>
			<input type='submit' name='submit' class='btn primary' value='Submit and Recommend Again' onclick='mysubmit() ' /></td>
		<td width='10%'>
			<input type='submit' name='next' class='btn info' value='Submit and Go to Summary report' onclick='finish() ' /></td>
		</tr> 
		</table>
	</form> ";
		 
echo " </div>
	</div>
	<a href='#' id='backtotop'><img src='./pages/img/top.png' alt='back to top' /></a>  
</body>
</html>";
?>