<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Agreement</title>
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
	    <div style="position:absolute;left:720px;top:88px"><h18>Version1.2. &nbsp Last modified: 12/19/2012.</h18></div>
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

      <div class="hero-unit2">
		<h13>Important Notice:<h13><br><h5>Please read the following Content Provider Agreement before uploading or inputting anything to ArticleNet.</h5>
		<hr width=80% size=4 color=#357EC7 style="filter:alpha(opacity=100,finishopacity=0,style=2)">
         
		<form action="u-step1.php" method="post">
		<table class="table1">
			<tr>
				<div style="float:center"><h13>Content Provider Agreement<br></h13></div>
				<h19><br>By uploading or providing any content (including but not limited to, Article list, Keywords list, PubType list, Author list, Feedback towards recommendations) to ArticleNet, You expressly grant to the Network Laboratory of Biomedical Informatics, The Ohio State University the right to study and use your inputted data by any means, including but not limited to, analyzing your provided articles, search strategies and judgments, developing future systems based on any result get from your SR scope, share your inputted data with our collaborators.<br><br> 
				ArticleNet reserves the right to delete, move, refuse to accept or process any content provided by you that, in its sole discretion, (i) violates or may violate this Content Provider Agreement, (ii) violates or may violate any of ArticleNet's policies or (iii) is deemed unacceptable in ArticleNet's sole discretion. <br><br>
				If you do not agree to the terms of this agreement, please do not upload or input anything to ArticleNet.</h19><br>
				<br><hr width=80% size=4 color=#357EC7 style="filter:alpha(opacity=100,finishopacity=0,style=2)">
			</tr>
			<tr>
				<?php
				echo " <input type='hidden' name='agree' value='true' />";
				?>	
			</tr>
			<tr>
				<td width="10%">
				<input type="submit" id="Button1" name="submit" class="btn primary" value="I agree"/></td> 
				<td width="10%">
				<a href="index.html"><input type="button" name="back" class="btn info" value="I don't agree" /></a></td>
			</tr>
        </table>
        </form>  
      </div>
    </div>
</body>
</html>
      
      
     