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

	//echo $_SESSION["tag"];
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
			<form action="u-recommend.php" method="get">
			<h5>Step3:Please set the Weight Parameters and the recommendation number per round.</h5>
			<hr width=80% size=4 color=#357EC7 style="filter:alpha(opacity=100,finishopacity=0,style=2); padding:0px;">
			<table class="table1">
			<tr>
				<td width="20%">
				<h4>Title Similarity</h4></td>
				<td width="80%">
				<input id="defaultSlider3" type="range" name="title" min="0" max="3" step="0.5" value="1" onchange="showValue6(this.value)" /> 
				<span id="range6">1</span>
			</tr>
			<tr>
				<td width="20%">
				<h4>Abstract Similarity</h4></td>
				<td width="80%">
				<input id="defaultSlider3" type="range" name="ab" min="0" max="3" step="0.5" value="1" onchange="showValue3(this.value)" /> 
				<span id="range3">1</span>
			</tr>
			<tr>
				<td width="20%">
				<h4>MeSH Similarity:</h4></td>
				<td width="80%">
				<input id="defaultSlider4" type="range" name="mh" min="0" max="3" step="0.5" value="1" onchange="showValue4(this.value)" />
				<span id="range4">1</span>
			</tr>
			<tr>
				<td width="20%">
				<h4>Author Similarity</h4></td>
				<td width="80%">
				<input id="defaultSlider3" type="range" name="author1" min="0" max="3" step="0.5" value="1" onchange="showValue7(this.value)" /> 
				<span id="range7">1</span>
			</tr>
			<tr>
				<td width="20%">
				<h4>Keywords:</h4></td>
				<td width="80%">
				<input id="defaultSlider1" type="range" name="key" min="0" max="3" step="0.5" value="0.5" onchange="showValue1(this.value)" /> 
				<span id="range1">0.5</span>
			</tr>
			<tr>
				<td width="20%">
				<h4>PubType:</h4></td>
				<td width="80%">
				<input id="defaultSlider2" type="range" name="type" min="0" max="3" step="0.5" value="0" onchange="showValue2(this.value)" /> 
				<span id="range2">0</span>
			</tr>
			<tr>
				<td width="20%">
				<h4>Author:</h4></td>
				<td width="80%">
				<input id="defaultSlider2" type="range" name="author2" min="0" max="3" step="0.5" value="0" onchange="showValue5(this.value)" /> 
				<span id="range5">0</span>
			</tr>
			<tr>
				<td width="30%">
				<h4>How many articles you would like ArticleNet to recommend each round?</h4></td>
				<td width="70%">
				<input type="text" name="step" id="step" class="input-small">
				<h8>(Increase this value if no article can be decided as included or excluded in one round.)</h8></td>
			</tr>
			<tr>
				<td width="10%"></td>
				<td width="10%">
				<input type="submit" name="submit" class="btn primary" value="Submit" /></td>
			</tr> 
			</table>
			</form>

		</div>
	</div>
</body>
</html>
      