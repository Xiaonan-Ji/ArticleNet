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
          <li><a href='agreement.php'>Run Your Own SR</a></li>
          <li class='active'><a href='step1.php'>Evaluate DERP Models</a></li>
       </ul>
       </div>
       
       <div class='hero-unit2'>
  
     ";
if(!isset($_SESSION["dir"]) || !isset($_GET["key"])|| !isset($_SESSION["topic"])){
	echo "<h16>Sorry we could not get your topic or weight settings, please set them first. </h16><br>";
	echo "<a href='index.html'><h15>Please Click here to Homepage</h15></a>";
	exit;
	}

$dir_base=$_SESSION["dir"];	 
$key = $_GET["key"];
$type = $_GET["type"];
$ab = $_GET["ab"];
$mh = $_GET["mh"];
$num = "1";
$title = $_GET["title"];
$author1 = $_GET["author1"];
$author2 = $_GET["author2"];

if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/UrinaryIncontinence/"){
	$total=327;
	$inclusion=40;
	$topic="UrinaryIncontinence";
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/NSAIDS/"){
	$total=393;
	$inclusion=41;
	$topic="NSAIDS";
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/Estrogens/"){
	$total=368;
	$inclusion=80;
	$topic="Estrogens";
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/OralHypoglycemics/"){
	$total=503;
	$inclusion=136;
	$topic="OralHypoglycemics";
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/Antihistamines/"){
	$total=310;
	$inclusion=16;
	$topic="Antihistamines";
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/AtypicalAntipsychotics/"){
	$total=1120;
	$inclusion=146;
	$topic="AtypicalAntipsychotics";
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/Opioids/"){
	$total=1915;
	$inclusion=15;
	$topic="Opioids";
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/ACEInhibitors/"){
	$total=2544;
	$inclusion=41;
	$topic="ACEInhibitors";
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/ADHD/"){
	$total=851;
	$inclusion=20;
	$topic="ADHD";
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/BetaBlockers/"){
	$total=2072;
	$inclusion=42;
	$topic="BetaBlockers";
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/CalciumChannelBlockers/"){
	$total=1218;
	$inclusion=100;
	$topic="CalciumChannelBlockers";
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/ProtonPumpInhibitors/"){
	$total=1333;
	$inclusion=51;
	$topic="ProtonPumpInhibitors";
}

if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/SkeletalMuscleRelaxants/"){
	$total=1643;
	$inclusion=9;
	$topic="SkeletalMuscleRelaxants";
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/Statins/"){
	$total=3464;
	$inclusion=85;
	$topic="Statins";
}
if($dir_base=="/var/www/cgi-bin/SR/SR-C/system/Triptans/"){
	$total=671;
	$inclusion=24;
	$topic="Triptans";
}


$step = $total;
echo "<h5>Performance of topic:" . $topic . "</h5>";
echo "<h5>Your weight parameters are:</h5>";
echo "<h4>Title Similarity: " . $title . "</br>Abstract Similarity: ".$ab."</br>MeSH Similarity: ".$mh."</br>Author Similarity: " . $author1 . "</br>Keywords: ".$_GET["key"]."</br>Publish Type: ".$type."</br>Author: " . $author2 . "</h4></br />";
		$args=$num." ".$step." " . $total . " " .  $inclusion ." " . $dir_base . " " . $key . " " . $type . " " . $ab . " " . $mh . " " . $title . " " . $author1 . " " . $author2 . " 1";
		if($args == "")
		echo "<h1>You did not enter any arguments.</h1>";
		else
		{
			echo "<h5>Articles are recommended as the followings...</h5>";
			$command = "/var/www/cgi-bin/SR/SR-C/evalue1 " . escapeshellcmd($args);
			passthru($command,$res);
		}

echo "<br />";
echo "<br />";
echo "      <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> "; 
 echo "	<table class='table1'>
          <tr>
	<td width='30%'></td>
           <td width='10%'>
           <a href='model2.php'><input type='button' name='next' class='btn primary' value='Run again' /></a></td>
           <td width='10%'>
           <a href='step1.php'><input type='button' name='back' class='btn default' value='Finish' /></a></td>
	<td width='50%'></td>
          </tr> 
         </table>
	</form> ";
		 
  echo " </div>
         </div>
	<a href='#' id='backtotop'><img src='./pages/img/top.png' alt='back to top' /></a>  
		 </body>
		 </html>";
  
?>