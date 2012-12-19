<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Research Model</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if IE]>
      <script type="text/javascript">document.createElement('h7');</script>
      <script type="text/javascript">document.createElement('h8');</script>
      <script type="text/javascript">document.createElement('h9');</script>
      <script type="text/javascript">document.createElement('h10');</script>
      <script type="text/javascript">document.createElement('h11');</script>
      <script type="text/javascript">document.createElement('h12');</script>
      <script type="text/javascript">document.createElement('h13');</script>
      <script type="text/javascript">document.createElement('h14');</script>
      <script type="text/javascript">document.createElement('h15');</script>
      <script type="text/javascript">document.createElement('h16');</script>
      <script type="text/javascript">document.createElement('h17');</script>
    <![endif]-->

    <!-- Le styles -->
    <link href="./pages/bootstrap-1.2.0.css" rel="stylesheet">
    <?php session_start();?>

  	<SCRIPT type="text/javascript">
	window.history.forward();     
	function noBack() 
		{ window.history.forward(); } 
	</SCRIPT> 

	<script language="javascript" type="text/javascript"> 
	function checkjs() 
	{ 
		document.getElementById('jscheck').style.display='none'; 
		//document.getElementById('normalcontent').style.display=''; 
		if(!(document.cookie || navigator.cookieEnabled)) 
		{ 
			alert('Please enable cookie to use our system.'); 
		} 
	}; 
	</script> 
  </head>


<body onload="noBack();checkjs();" onpageshow="if (event.persisted) noBack();" onunload=""> 
	<div class="topbar">
		<div class="fill">
			<div class="container">
			<h3><a href="#" ><tt><strong>ArticleNet</strong></tt></a></h3>
			<div style='position:absolute;left:720px;top:88px'><h18>Version1.2. &nbsp Last modified: 12/19/2012.</h18></div>
			<img src="./pages/img/logo.png" alt="logo" class="logo">
			</div>
		</div>
	</div>
    
	<div class="container">
		<div>
			<ul class="tabs">
				<li><a href="index.html">Home</a></li>
				<li><a href="instruction.html">Learn about us</a></li>
				<li><a href="agreement.php">Run Your Own SR</a></li>
				<li class='active'><a href="step1.php">Evaluate DERP Models</a></li>
			</ul>
		</div>

		<div id="jscheck"><h12>Please enable javascript to use our system.</h12></div>        
		<div class="hero-unit2">
			<h14>Need to download Article list/Keywords of DERP Model?</h14>
			<a href="getfile.php"><input type="button" class="btn success" value=">>Download Research Models"></a>
			<hr width=80% size=4 color=#357EC7 style="filter:alpha(opacity=100,finishopacity=0,style=2)">  
			
			<h17>Evaluate a model of DERP. Please select one article topic from the following list. </h17>  
			<form action="model1.php" method="get">
			<h4><input type="radio" name="topic" value="UrinaryIncontinence" /> UrinaryIncontinence</h4>
			
			<h4><input type="radio" name="topic" value="NSAIDS" /> NSAIDS</h4>

			<h4><input type="radio" name="topic" value="Estrogens" /> Estrogens</h4>

			<h4><input type="radio" name="topic" value="OralHypoglycemics" /> OralHypoglycemics</h4>

			<h4><input type="radio" name="topic" value="Antihistamines" /> Antihistamines</h4>

			<h4><input type="radio" name="topic" value="AtypicalAntipsychotics" /> AtypicalAntipsychotics</h4>

			<h4><input type="radio" name="topic" value="Opioids" /> Opioids</h4>

			<h4><input type="radio" name="topic" value="ACEInhibitors" /> ACEInhibitors</h4>

			<h4><input type="radio" name="topic" value="ADHD" /> ADHD</h4>

			<h4><input type="radio" name="topic" value="BetaBlockers" /> BetaBlockers</h4>

			<h4><input type="radio" name="topic" value="CalciumChannelBlockers" /> CalciumChannelBlockers</h4>

			<h4><input type="radio" name="topic" value="ProtonPumpInhibitors" /> ProtonPumpInhibitors</h4>

			<h4><input type="radio" name="topic" value="SkeletalMuscleRelaxants" /> SkeletalMuscleRelaxants</h4>

			<h4><input type="radio" name="topic" value="Statins" /> Statins</h4>

			<h4><input type="radio" name="topic" value="Triptans" /> Triptans</h4>

			<br />
			<input type="submit" value="Continue" class="btn primary" />
			<br />
			</form>
			<h7>To use this function, your explore should support javascript and cookie, if so please enbale them.</h7>    
   <?php 
if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
if(!($con = ssh2_connect("netlab.bmi.osumc.edu", 22))){
    echo "fail: unable to establish connection\n";
} else {
    if(!ssh2_auth_password($con, "ji02", "jxn2025133")) {
        echo "fail: unable to authenticate\n";
    } 
	else {
        if (!($stream = ssh2_exec($con, "ls" ))) {
            echo "fail: unable to execute command\n";
        } 
		else {
            stream_set_blocking($stream, true);
            $data = "";
            while ($buf = fread($stream,4096)) {
                $data .= $buf;
            }
            fclose($stream);
        }
    }
}
?>
		</div>
	</div>
	<a href="#" id="backtotop"><img src="./pages/img/top.png" alt="back to top" /></a>  
</body>
</html>      
      
     