<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Get Start</title>
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
	<?php
	session_start();
	?>
	
// Verification code
	<script language="javascript" type="text/javascript">
	var code ; 
	function createCode()
	{ 
		code = "";
		var codeLength = 6;
		var checkCode = document.getElementById("checkCode");
		var selectChar = new Array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        
		for(var i=0;i<codeLength;i++)
		{       
			var charIndex = Math.floor(Math.random()*36);
			code +=selectChar[charIndex];      
        }
// alert(code);
		if(checkCode)
		{
			checkCode.className="code";
			checkCode.value = code;
        }   
	}

      function validate ()
      {
		var inputCode = document.getElementById("input1").value;
		if(inputCode.length <=0)
        {
            alert("Please enter the Verification code.");
			return false;
        }
		else if(inputCode != code )
        {
			alert("The Verification code is not correct, please try again.");
			createCode();
			return false;
        }
		else
        {
			alert("OK. We are processing your article list...");
			return true;
        }      
	}     

// check if js and cookie are enabled	
	function checkjs() 
	{ 
		document.getElementById('jscheck').style.display='none'; 
		if(!(document.cookie || navigator.cookieEnabled)) 
		{ 
			alert('Please enable cookie to use our system.'); 
		} 
	}
	</script> 

	<style type="text/css">
         .code
        {
             background-image:url(code.jpg);
             font-family:Arial;
             font-style:italic;
             color:Navy;
             border:0;
             padding:2px 3px;
             letter-spacing:3px;
             font-weight:bolder;
        }
         .unchanged
        {
             border:0;
        }
    </style>

	<SCRIPT type="text/javascript">
		window.history.forward();     
		function noBack() 
		{ window.history.forward(); } 
	</SCRIPT> 
</head>


<body onload="noBack();checkjs();" onpageshow="if (event.persisted) noBack();" onunload=""> 

	<div class="topbar">
		<div class="fill">
			<div class="container">
			<h3><a href="#" ><tt><strong>ArticleNet</strong></tt></a></h3>
			<div style="position:absolute;left:720px;top:88px"><h18>Version1.2. &nbsp Last modified: 11/30/2012.</h18></div>
			<img src="./pages/img/logo.png" alt="logo" class="logo">
			</div>
		</div>
	</div>
    
	<div class="container">
		<div>
		<ul class="tabs">
			<li><a href="index.html">Home</a></li>
			<li><a href="instruction.html">Learn about us</a></li>
			<li class='active'><a href="#">Run Your Own SR</a></li>
			<li><a href="step1.php">Evaluate DERP Models</a></li>
		</ul>
		</div>
		<div id="jscheck"><h12>Please enable javascript to use our system.</h12></div>

<?php
if(isset($_POST["agree"])){
	$_SESSION["agree"]=$_POST["agree"];
} 
if(!isset($_SESSION["agree"])){
	echo "<h16>You can only access this function after reading and agreeing the Content Provider Agreement. </h16><br>";
	echo "<a href='agreement.php'><h15>Please Click here to read the Content Provider Agreement.</h15></a>";
	exit;
}
?>       
		<div class="hero-unit2">
			<h5>Step1:Please upload your Article List here.</h5>
			<h11>Please provide articles in MEDLINE format, and your article list should contain no less than 2 and no more than 500 articles.</h11><br>
			<hr width=80% size=4 color=#357EC7 style="filter:alpha(opacity=100,finishopacity=0,style=2)">
         
			<form action="u-upload.php" method="post" enctype="multipart/form-data" onsubmit="return validate();">
			<table class="table1">
			<tr>
				<td width="20%">
				<h4>Upload the File:</h4></td>
				<td width="80%">
				<input type="file" name="file" id="file" class="btn defult" width="100px"/> </td>
			</tr>
			<tr>
				<td width="20%">
				<h4>Please enter code:</h4></td>
				<td width="20%">
				<input  type="text" class="input-small" onclick="createCode()" id="input1" />
				<h8><=Click and get code</h8> 
				<input type="text"   id="checkCode" class="unchanged" style="width: 80px"  /></td>
			</tr>
			<tr>
<?php
	//session_start();
	$_SESSION['key1'] = mt_rand(1, 1000);
	echo " <input type='hidden' name='key1' value='" . $_SESSION['key1'] . "' />";
?>	
			</tr>
			<tr>
				<td width="10%">
				<input type="submit" id="Button1" name="submit" class="btn primary" value="Submit and Upload"/></td>
			</tr> 
			</table>
			</form>
<?php 
if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
if(!($con = ssh2_connect("netlab.bmi.osumc.edu", 22))){
    echo "fail: unable to establish connection\n";
} else {
    if(!ssh2_auth_password($con, "ji02", "jxn2025133")) {
        echo "fail: unable to authenticate\n";
    } else {
        if (!($stream = ssh2_exec($con, "ls" ))) {
            echo "fail: unable to execute command\n";
        } else {
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

			<h7>To use this function, your explore should support javascript and cookie, if so please enbale them.</h7>

		</div>
	</div>
</body>
</html>
        