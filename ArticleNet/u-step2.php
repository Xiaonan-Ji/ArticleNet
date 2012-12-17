<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Step 2</title>
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

    <script language="javascript" src="displaypaper.js"></script>
    <?php session_start();?>

	<script type="text/javascript">   
	function banBackSpace(e){      
    var ev = e || window.event;     
    var obj = ev.target || ev.srcElement;     
       
    var t = obj.type || obj.getAttribute('type');     
       
    var vReadOnly = obj.getAttribute('readonly');   
    var vEnabled = obj.getAttribute('enabled');   

    vReadOnly = (vReadOnly == null) ? false : vReadOnly;   
    vEnabled = (vEnabled == null) ? true : vEnabled;   
          
    var flag1=(ev.keyCode == 8 && (t=="password" || t=="text" || t=="textarea")    
                && (vReadOnly==true || vEnabled!=true))?true:false;   
      
    var flag2=(ev.keyCode == 8 && t != "password" && t != "text" && t != "textarea")   
                ?true:false;           
       
    if(flag2){   
        return false;   
    }   
    if(flag1){      
        return false;      
    }      
	}   
    
	document.onkeypress=banBackSpace;   
	document.onkeydown=banBackSpace;   
  
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
//session_start();
if(!isset($_SESSION["dir"]) || !isset($_SESSION["tag"])){
	echo "<h16>Sorry we could not find your workspace/session, please start over. </h16><br>";
	echo "<a href='index.html'><h15>Please Click here to Homepage</h15></a>";
	exit;
}
?>    
			<h5>Step2:Please upload or enter your Keywords, PubType and Authors are optional.</h5>
			<h11>Importnat notice! If entering, please seperate different phrases with a comma!</h11><br>
			<h11> (i.e. "toxic,headache,quality of life,blood pressure,adult,adverse" or "Systematic review, clinical trial") </h11><br>
			<h11>For Authors, please put firstname(middle name) before lastname! (i.e. "Logon Freeman, A D Jones")</h11>
			<br/>
			<hr width=80% size=4 color=#357EC7 style="filter:alpha(opacity=100,finishopacity=0,style=2)">
         
			<form action="u-process.php" method="post" enctype="multipart/form-data">
			<table class="table1">
			<tr>
				<td width="20%">
				<h4>Enter Keywords*:</h4></td>
				<td width="80%">
				<input type="text" name="keywords" id="keywords" class="input-large"> </td>
			</tr>
			<tr> 
				<td width="20%">
				<h4>Or Upload Keywords*:</h4></td>
				<td width="80%">
				<input type="file" name="file[]" id="key" class="btn defult"> </td>
			</tr>
			<tr>
				<td width="20%">
				<h4>Enter PubTypes:</h4></td>
				<td width="80%">
				<input type="text" name="pubtypes" id="pubtypes" class="input-large"> </td>
			</tr>
			<tr> 
				<td width="20%">
				<h4>Or Upload PubTypes:</h4></td>
				<td width="80%">
				<input type="file" name="file[]" id="pub" class="btn defult"> </td>
			</tr>
			<tr>
				<td width="20%">
				<h4>Enter Authors:</h4></td>
				<td width="80%">
				<input type="text" name="authors" id="authors" class="input-large"> </td>
			</tr>
			<tr> 
				<td width="20%">
				<h4>Or Upload Authors:</h4></td>
				<td width="80%">
				<input type="file" name="file[]" id="au" class="btn defult"> </td>
			</tr>
			<tr>
			<?php
				$_SESSION['key1'] = mt_rand(1, 1000);
				echo " <input type='hidden' name='key1' value='" . $_SESSION['key1'] . "' />";
			?>
			</tr>	
			<tr>
				<td width="10%">
				<input type="submit" name="submit" class="btn primary" value="Submit and Upload" /></td>
				<td width="10%">
				<input type="reset" name="reset" class="btn default" value="Reset" /></td>
			</tr> 
			</table>
			</form>

		</div>
	</div>
</body>
</html>   
  