<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Step 3</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="REFRESH" content="5;url=netlab.bmi.osumc.edu/ArticleNet/u-recommend-default.php">

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
    <script type="text/javascript">
	function showValue1(newValue)
	{
		document.getElementById("range1").innerHTML=newValue;
	}

	function showValue2(newValue)
	{
		document.getElementById("range2").innerHTML=newValue;
	}

	function showValue3(newValue)
	{
		document.getElementById("range3").innerHTML=newValue;
	}

	function showValue4(newValue)
	{
		document.getElementById("range4").innerHTML=newValue;
	}
	function showValue5(newValue)
	{
		document.getElementById("range5").innerHTML=newValue;
	}

	function showValue6(newValue)
	{
		document.getElementById("range6").innerHTML=newValue;
	}

	function showValue7(newValue)
	{
		document.getElementById("range7").innerHTML=newValue;
	}
	</script>
	<SCRIPT type="text/javascript">
		window.history.forward();     
		function noBack() 
			{ window.history.forward(); } 
	</SCRIPT> 
</head>


<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload=""> 

	<div class="topbar">
		<div class="fill">
			<div class="container">
			<h3><a href="#" ><tt><strong>ArticleNet</strong></tt></a></h3>
			<div style='position:absolute;left:720px;top:88px'><h18>Version1.2. &nbsp Last modified: 11/30/2012.</h18></div>
			<img src="./pages/img/logo.png" alt="logo" class="logo">
			</div>
		</div>
	</div>
    
	<div class="container">
		<div>
		<ul class="tabs">
			<li><a href="index.html">Home</a></li>
			<li><a href="instruction.html">Learn about us</a></li>
			<li class="active"><a href="u-step1.php">Run Your Own SR</a></li>
			<li><a href="step1.php">Evaluate DERP Models</a></li>
		</ul>
		</div>
       
		<div class="hero-unit2">
	<?php 
	session_start();

	if(!isset($_SESSION["dir"]) || !isset($_SESSION["tag"])){
	echo "<h16>Sorry we could not find your workspace/session, please start over. </h16><br>";
	echo "<a href='index.html'><h15>Please Click here to Homepage</h15></a>";
	exit;
	}
	// this is a return back from other pages
	if($_SESSION["tag"]=="1"||$_SESSION["tag"]=="2"||$_SESSION["tag"]=="3"||$_SESSION["tag"]=="4"){
		$_SESSION["start"]="true";
	}
	// session has not started
	else if($_SESSION["tag"]==-1){
		$_SESSION["tag"]="0";
		$_SESSION["start"]="false";
	}
	?>
	
			<h2>Recommendation will start in, </h2>
			<div id="information" style="width">
				<br><br><h7>>>>If you want to adjust the parameters by yourself, </h7><br>
				<h7><a href="u-step3.php">Please click here</a></h7><br>
	
	<?php
		ob_end_flush(); 
		set_time_limit(0);
		for($i=0; $i<=5; $i++){
			flush();
			$left = 5-$i;
			echo '<script language="javascript">
   			 		document.getElementById("information").innerHTML="<h2>'.$left.' seconds</h2>";
   			 	</script>';
			echo "<br><br><h7>>>>If you want to adjust the parameters by yourself, </h7><br>
			     <h7><a href='u-step3.php'>Please click here</a></h7><br>";

       		// Sleep one second so we can see the delay
   			 sleep(1);
		}
		//header('Location: u-recommend-default.php');
	?>
	
	<script language="javascript" type="text/javascript">
        window.setTimeout('window.location="u-recommend-default.php"; ',0);
	</script>

			</div>
		</div>
	</div>
</body>
</html>
      