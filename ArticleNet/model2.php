<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Step 3</title>
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

	function default1()
	{
		document.getElementById("range1").innerHTML=1;
		document.getElementById("Slider1").value=1;
		document.getElementById("range2").innerHTML=1;
		document.getElementById("Slider2").value=1;
		document.getElementById("range3").innerHTML=1;
		document.getElementById("Slider3").value=1;
		document.getElementById("range4").innerHTML=0;
		document.getElementById("Slider4").value=0;
		document.getElementById("range5").innerHTML=0;
		document.getElementById("Slider5").value=0;	
		document.getElementById("range6").innerHTML=0;
		document.getElementById("Slider6").value=0;
		document.getElementById("range7").innerHTML=0;
		document.getElementById("Slider7").value=0;
	}

	function default2()
	{
		document.getElementById("range1").innerHTML=1;
		document.getElementById("Slider1").value=1;
		document.getElementById("range2").innerHTML=1;
		document.getElementById("Slider2").value=1;
		document.getElementById("range3").innerHTML=1;
		document.getElementById("Slider3").value=1;
		document.getElementById("range4").innerHTML=1;
		document.getElementById("Slider4").value=1;
		document.getElementById("range5").innerHTML=0;
		document.getElementById("Slider5").value=0;	
		document.getElementById("range6").innerHTML=0;
		document.getElementById("Slider6").value=0;
		document.getElementById("range7").innerHTML=0;
		document.getElementById("Slider7").value=0;
	}

	function default3()
	{
		document.getElementById("range1").innerHTML=1;
		document.getElementById("Slider1").value=1;
		document.getElementById("range2").innerHTML=1;
		document.getElementById("Slider2").value=1;
		document.getElementById("range3").innerHTML=1;
		document.getElementById("Slider3").value=1;
		document.getElementById("range4").innerHTML=0;
		document.getElementById("Slider4").value=0;
		document.getElementById("range5").innerHTML=0.5;
		document.getElementById("Slider5").value=0.5;	
		document.getElementById("range6").innerHTML=0;
		document.getElementById("Slider6").value=0;
		document.getElementById("range7").innerHTML=0;
		document.getElementById("Slider7").value=0;
}

	function default4()
	{
		document.getElementById("range1").innerHTML=1;
		document.getElementById("Slider1").value=1;
		document.getElementById("range2").innerHTML=1;
		document.getElementById("Slider2").value=1;
		document.getElementById("range3").innerHTML=1;
		document.getElementById("Slider3").value=1;
		document.getElementById("range4").innerHTML=1;
		document.getElementById("Slider4").value=1;
		document.getElementById("range5").innerHTML=0.5;
		document.getElementById("Slider5").value=0.5;	
		document.getElementById("range6").innerHTML=0;
		document.getElementById("Slider6").value=0;
		document.getElementById("range7").innerHTML=0;
		document.getElementById("Slider7").value=0;
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
				<li><a href="agreement.php">Run Your Own SR</a></li>
				<li class="active"><a href="step1.php">Evaluate DERP Models</a></li>
			</ul>
		</div>
       
		<div class="hero-unit2">
<?php
	if(!isset($_SESSION["dir"])|| !isset($_SESSION["topic"])){
	echo "<h16>Sorry we could not get your topic, please select it first. </h16><br>";
	echo "<a href='index.html'><h15>Please Click here to Homepage</h15></a>";
	exit;
	}
?>
			<h5>Please set the weight parameters: </h5><br/>
			<hr width=80% size=4 color=#357EC7 style="filter:alpha(opacity=100,finishopacity=0,style=2)">
			<table class="tablenew">
			<tr>
				<td width="70%">
				<div>
				<form action="model3.php" method="get">
					<table class="table1">
					<tr>
						<td width="30%">
						<h4>Title Similarity</h4></td>
						<td width="70%">
						<input id="Slider1" type="range" name="title" min="0" max="3" step="0.5" value="1" onchange="showValue1(this.value)" /> 
						<span id="range1">1</span>
					</tr>
					<tr>
						<td width="30%">
						<h4>Abstract Similarity</h4></td>
						<td width="70%">
						<input id="Slider2" type="range" name="ab" min="0" max="3" step="0.5" value="1" onchange="showValue2(this.value)" /> 
						<span id="range2">1</span>
					</tr>
					<tr>
						<td width="30%">
						<h4>MeSH Similarity:</h4></td>
						<td width="70%">
						<input id="Slider3" type="range" name="mh" min="0" max="3" step="0.5" value="1" onchange="showValue3(this.value)" />
						<span id="range3">1</span>
					</tr>
					<tr>
						<td width="30%">
						<h4>Author Similarity</h4></td>
						<td width="70%">
						<input id="Slider4" type="range" name="author1" min="0" max="3" step="0.5" value="1" onchange="showValue4(this.value)" /> 
						<span id="range4">1</span>
					</tr>
					<tr>
						<td width="30%">
						<h4>Keywords:</h4></td>
						<td width="70%">
						<input id="Slider5" type="range" name="key" min="0" max="3" step="0.5" value="0.5" onchange="showValue5(this.value)" /> 
						<span id="range5">0.5</span>
					</tr>
					<tr>
						<td width="30%">
						<h4>PubType:</h4></td>
						<td width="70%">
						<input id="Slider6" type="range" name="type" min="0" max="3" step="0.5" value="0" onchange="showValue6(this.value)" /> 
						<span id="range6">0</span>
					</tr>
					<tr>
						<td width="30%">
						<h4>Author:</h4></td>
						<td width="70%">
						<input id="Slider7" type="range" name="author2" min="0" max="3" step="0.5" value="0" onchange="showValue7(this.value)" /> 
						<span id="range7">0</span>
					</tr>
					<tr>
						<td width="15"></td>
						<td width="10%">
						<input type="submit" name="submit" class="btn primary" value="Submit" /></td>
						<td width="75"></td>
					</tr> 
					</table>
				</form>
				</div>
				</td>
				<td width="30%">
				<div>
					<h5>Click and Apply Default Settings</h5><br/>
					<h17>Ti+Ab+MeSH</h17><br>
					<input type="button" name="button1" class="btn info" value="Default1" onclick="default1()" /><br><br>
					<h17>Ti+Ab+MeSH+Au</h17><br>
					<input type="button" name="button2" class="btn info" value="Default2" onclick="default2()" /><br><br>
					<h17>Ti+Ab+MeSH+Keyword</h17><br>
					<input type="button" name="button3" class="btn info" value="Default3" onclick="default3()" /><br><br>
					<h17>Ti+Ab+MeSH+Au+Keyword</h17><br>
					<input type="button" name="button4" class="btn info" value="Default4" onclick="default4()" /><br><br>
				</div>
				</td>
			</tr>
			</table>
		</div>
	</div>
</body>
</html>
      