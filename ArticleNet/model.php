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
  </head>

<body>

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
			<li><a href='u-step1.php'>Run Your Own SR</a></li>
			<li class='active'><a href='step1.php'>Evaluate DERP Models</a></li>
		</ul>
		</div>
       
		<div class='hero-unit2'>
     ";

$topic=$_GET["topic"];
$old_base = "/var/www/cgi-bin/SR/SR-C/system/";
$dir_base=$old_base . $topic . "/";
$_SESSION["dir"]=$dir_base;
echo "the dir_base is " . $_SESSION["dir"];

if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/UrinaryIncontinence/"){
	$total=327;
	$inclusion=40;
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/NSAIDS/"){
	$total=393;
	$inclusion=41;
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/Estrogens/"){
	$total=368;
	$inclusion=80;
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/OralHypoglycemics/"){
	$total=503;
	$inclusion=136;
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/Antihistamines/"){
	$total=310;
	$inclusion=16;
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/AtypicalAntipsychotics/"){
	$total=1120;
	$inclusion=145;
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/Opioids/"){
	$total=1915;
	$inclusion=15;
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/ACEInhibitors/"){
	$total=2544;
	$inclusion=41;
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/ADHD/"){
	$total=851;
	$inclusion=20;
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/BetaBlockers/"){
	$total=2072;
	$inclusion=42;
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/CalciumChannelBlockers/"){
	$total=1218;
	$inclusion=100;
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/ProtonPumpInhibitors/"){
	$total=1333;
	$inclusion=51;
}

if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/SkeletalMuscleRelaxants/"){
	$total=1643;
	$inclusion=9;
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/Statins/"){
	$total=3464;
	$inclusion=85;
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/Triptans/"){
	$total=671;
	$inclusion=24;
}

echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";

//ssh to account
if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
// log in at server1.example.com on port 22
if(!($con = ssh2_connect("netlab.bmi.osumc.edu", 22))){
    echo "fail: unable to establish connection\n";
} else {
    // try to authenticate with username root, password secretpassword
    if(!ssh2_auth_password($con, "ji02", "jxn2025133")) {
        echo "fail: unable to authenticate\n";
    } else {
        // allright, we're in!
        echo "okay: logged in...\n";
	echo "<br />";
    }
}	   
	   
// get the numbet of articles
$PATH=$dir_base;
$num=sizeof(scandir($PATH));
$num=($num>2)?$num-2:0;
$articlenum = $num -4;

// call process to generate similarity, and other scores	 
echo "<br />";
$args = $dir_base . " " . $total;
if($args == "")
echo "<h1>You did not enter any arguments.</h1>";
else
{
	echo "<h5>Processing your request...</h5>";
	ob_end_flush(); 
	set_time_limit(0);
	flush();
	for($i=0;$i<10;$i++){ 
		echo ">"; 
		flush(); 
		sleep(1); 
	} 
	$command = "/var/www/cgi-bin/SR/SR-C/process " . escapeshellcmd($args);
	passthru($command,$res);
}
		
echo "   <table class='table1'>
          <tr>
           <td width='10%'>
           <a href='model2.html'><input type='button' name='next' class='btn primary' value='Continue' /></a></td>
           <td width='10%'>
           <a href='step1.php'><input type='button' name='back' class='btn default' value='Return Back' /></a></td>
          </tr> 
         </table> ";
		 
echo " </div>
   </div>
</body>
</html>";
?>