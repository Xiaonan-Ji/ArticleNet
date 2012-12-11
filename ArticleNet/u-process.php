<?php
session_start();
echo "
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
       
		<div class='hero-unit2'>
     ";
	 
if(!isset($_SESSION["dir"]) || !isset($_SESSION["tag"])){
	echo "<h16>Sorry we could not find your workspace/session, please start over. </h16><br>";
	echo "<a href='index.html'><h15>Please Click here to Homepage</h15></a>";
	exit;
}

if(isset($_POST['submit']) && $_POST['key1'] == $_SESSION['key1']){
$dir_base=$_SESSION["dir"];
//echo $_SESSION["dir"];
$old_base = "/var/www/cgi-bin/SR/SR-C/";
$key = $_POST["keywords"];
$pub = $_POST["pubtypes"];
$au = $_POST["authors"];

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

//process the keywords list
if ($_FILES['file']['type'][0] != "text/plain") {
	if(empty($key)){
		echo "<h16>Please enter or upload your keywords list. </h16><br>";
		echo "<a href='u-step2.php'><h15>Please Click here to return back.</h15></a>";
		exit;
	}
        echo "<h4>You have entered your keywords list</h4>";
		//$newkey=str_replace(",","\n",$key);	
		//echo $newkey;
		$newkey = explode(',',$key); 
		for($index=0; $index<count($newkey); $index++){ 
			echo $newkey[$index];
			echo "</br>"; 		
			$cmd4 = "echo ". $newkey[$index].  " >> " . $dir_base . "keywords.txt";
			if (!($stream = ssh2_exec($con, $cmd4))) {
				echo "fail: unable to execute command\n";
			} 
			else {
				//echo "keywords has been updated...\n";
				//echo '<br />' ;
				// collect returning data from command
				stream_set_blocking($stream, true);
				$data = "";
				while ($buf = fread($stream,4096)) {
					$data .= $buf;
				}
				fclose($stream);
			}
		}
		//echo "<h4>Displaying your keywords list: </h4>";
		//echo $key;
		//echo "<br />";
		echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";  
		}
else{
//diaplay the upload information
		echo "<h4>Your have uploaded Keywords list:</h4>";	
		echo "Upload: " . $_FILES["file"]["name"][0] . "<br />";
		echo "Type: " . $_FILES["file"]["type"][0] . "<br />";
		echo "Size: " . ($_FILES["file"]["size"][0] / 1024) . " Kb<br />";
		//echo "Temp file: " . $_FILES["file"]["tmp_name"][0] . "<br />";
		echo "<h4>Displaying your keywords list: </h4>";
		readfile($_FILES["file"]["tmp_name"][0]); 	 
		echo '<br />';
		$tmp_name1 = $_FILES["file"]["tmp_name"][0];
       		 $name1 = $_FILES["file"]["name"][0];
		if(move_uploaded_file($tmp_name1, $dir_base ."keywords.txt")){
          //echo 'stored in: ', $dir_base;
		  //echo '<br />';
		  echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";  
	   }
}

if ($_FILES['file']['type'][1] != "text/plain") {
	if(!empty($pub)){
        echo "<h4>You have entered your Publish Type list</h4>";}
		$newpub = explode(',',$pub); 
		for($index=0; $index<count($newpub); $index++){ 
			echo $newpub[$index];
			echo "</br>"; 		
			$cmd4 = "echo ". $newpub[$index].  " >> " . $dir_base . "PubType.txt";
			if (!($stream = ssh2_exec($con, $cmd4))) {
				echo "fail: unable to execute command\n";
			} 
			else {
				//echo "pubtype has been updated...\n";
				//echo '<br />' ;
				// collect returning data from command
				stream_set_blocking($stream, true);
				$data = "";
				while ($buf = fread($stream,4096)) {
					$data .= $buf;
				}
				fclose($stream);
			}
		}
   if(!empty($pub)){
	//echo "<h4>Displaying your publish type list: </h4>";
	//echo $pub;
	//echo "<br />";
	echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";  
	}
}
	else{
		echo "<h4>Your have uploaded PubType list:</h4>";	
		echo "Upload: " . $_FILES["file"]["name"][1] . "<br />";
		echo "Type: " . $_FILES["file"]["type"][1]. "<br />";
		echo "Size: " . ($_FILES["file"]["size"][1]/ 1024) . " Kb<br />";
		//echo "Temp file: " . $_FILES["file"]["tmp_name"][1] . "<br />";
		echo "<h4>Displaying your publish type list: </h4>";
		readfile($_FILES["file"]["tmp_name"][1]); 
		echo '<br />';
		$tmp_name2 = $_FILES["file"]["tmp_name"][1];
        $name2 = $_FILES["file"]["name"][1];	   
        if(move_uploaded_file($tmp_name2 ,$dir_base . "PubType.txt")){
          //echo 'stored in: ',$dir_base;
		  //echo '<br />';
		  echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";  
	   }
}
	
if ($_FILES['file']['type'][2] != "text/plain") {
		if(!empty($au)){
        echo "<h4>You have entered your author list</h4>";}
		$newau = explode(',',$au); 
		for($index=0; $index<count($newau); $index++){ 
			echo $newau[$index];
			echo "</br>"; 		
			$cmd4 = "echo ". $newau[$index].  " >> " . $dir_base . "authors.txt";
			if (!($stream = ssh2_exec($con, $cmd4))) {
				echo "fail: unable to execute command\n";
			} 
			else {
				//echo "pubtype has been updated...\n";
				//echo '<br />' ;
				// collect returning data from command
				stream_set_blocking($stream, true);
				$data = "";
				while ($buf = fread($stream,4096)) {
					$data .= $buf;
				}
				fclose($stream);
			}
		}
		if(!empty($au)){
		//echo "<h4>Displaying your author list: </h4>";
		//echo $au;
		//echo "<br />";
		echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";  
		}
}
else{
		echo "<h4>Your have uploaded Author list:</h4>";	
		echo "Upload: " . $_FILES["file"]["name"][2] . "<br />";
		echo "Type: " . $_FILES["file"]["type"][2]. "<br />";
		echo "Size: " . ($_FILES["file"]["size"][2]/ 1024) . " Kb<br />";
		//echo "Temp file: " . $_FILES["file"]["tmp_name"][2] . "<br />";
		echo "<h4>Displaying your author list: </h4>";
		readfile($_FILES["file"]["tmp_name"][2]); 
		echo '<br />';
		$tmp_name3 = $_FILES["file"]["tmp_name"][2];
       		 $name3 = $_FILES["file"]["name"][2];
       		 if(move_uploaded_file($tmp_name3, $dir_base ."authors.txt")){
         	 //echo 'stored in: ', $dir_base;
		  //echo '<br />';
		  echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";    
	   }
}

// get the numbet of articles
echo "<h5>We are processing your request, it may take 5 seconds to 3 minutes...Please do not refresh this page.</h5>";
$articlenum=$_SESSION["articlenum"];
echo "There are " . $articlenum. " articles in total.";
echo "<br>";

echo "<div id='progress' style='width:500px;border:1px solid #ccc;'></div>";
echo "<div id='information' style='width'>";

// call process to generate similarity, and other scores
	    echo "<br />";
		$args = $dir_base . " " . $articlenum;
		if($args == "")
		echo "<h1>You did not enter any arguments.</h1>";
		else
		{
			//ob_end_flush(); 
			set_time_limit(0);
			flush(); 

			//echo "<h5>We are processing your request, it may take 5 seconds to 3 minutes...Please do not refresh this page.</h5>";
			$command = "/var/www/cgi-bin/SR/SR-C/process " . escapeshellcmd($args) . " > " . $dir_base . "printout.txt &";
			exec($command);

			//The processing bar
			// Total processes
			if(1 <= $articlenum && $articlenum <= 100){
				$total = 3;}
			else if(100 < $articlenum && $articlenum <= 250){
				$total = 5;}
			else if(250 < $articlenum && $articlenum <=330){
				$total = 6;}
			else if(330 < $articlenum && $articlenum <= 400){
				$total = 8;}
			else if(400 < $articlenum && $articlenum <= 500){
				$total = 12;}
			else if(500 < $articlenum && $articlenum <= 600){
				$total = 14;}
			else if(600 < $articlenum && $articlenum <= 700){
				$total = 18;}
			else if(600 < $articlenum && $articlenum <= 800){
				$total = 25;}
			else if(800 < $articlenum && $articlenum <= 1000){
				$total = 36;}
			else if(1000 < $articlenum && $articlenum <= 1200){
				$total = 50;}
			else if(1200 < $articlenum && $articlenum <= 1400){
				$total = 62;}
			else if(1400 < $articlenum && $articlenum <= 1600){
				$total = 71;}
			else if(1600 < $articlenum && $articlenum <= 1800){
				$total = 110;}
			else if(1800 < $articlenum && $articlenum <= 2000){
				$total = 135;}
			else if(2000 < $articlenum && $articlenum <= 2200){
				$total = 150;}
			else if(2200 < $articlenum && $articlenum <= 2400){
				$total = 180;}
			else if(2400 < $articlenum && $articlenum <= 2600){
				$total = 210;}
			else if(2600 < $articlenum && $articlenum <= 2800){
				$total = 250;}
			else if(2800 < $articlenum && $articlenum <= 3000){
				$total = 290;}
			else if(3000 < $articlenum && $articlenum <= 3200){
				$total = 320;}
			else if(3200 < $articlenum && $articlenum <= 3400){
				$total = 340;}
			else if(3400 < $articlenum && $articlenum <= 3600){
				$total = 390;}
			else if(3600 < $articlenum && $articlenum <= 3800){
				$total = 440;}
			else if(3800 < $articlenum && $articlenum <= 4000){
				$total = 490;}
			else if(4000 < $articlenum){
				$total = 500;}

			// Loop through process
			for($i=1; $i<=$total; $i++){
   				 // Calculate the percentation
   				 $percent = intval($i/$total * 100)."%";
				 $left = $total-$i;
				 
    				// Javascript for updating the progress bar and information
   				 echo '<script language="javascript">
   					 document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
   			 		 document.getElementById("information").innerHTML="'.$left.' seconds left.";
   			 		 </script>';
 
   				// This is for the buffer achieve the minimum size in order to flush data
   			 	echo str_repeat(' ',1024*64);
 
    				// Send output to browser immediately
    				flush();
 
    				// Sleep one second so we can see the delay
   			 	sleep(1);
			}
 
			// Tell user that the process is completed
			echo '<script language="javascript">document.getElementById("information").innerHTML="Process is completing..."</script>';

		}
		
// check if the input keywords works
	$file =  $dir_base . "KeyScore.txt";
	$file_2 =  $dir_base . "matrixAU.txt";
	echo "<br>";
	while(1){
		if (file_exists($file) && file_exists($file_2)) {
			echo "Process is completed!</br>";
    			break;
		} 
		else {
			echo "We will get there soon...<br>";
			for($i=1; $i<=5; $i++){
				flush();
       			// Sleep one second so we can see the delay
   			 	sleep(1);
			}
		}
	}
		
	$handle = fopen ($file, "r");
	$sum="0";
	while (!feof ($handle)) {
		$buffer = fgets($handle, 4096);
		$result=intval($buffer);
		$sum=$sum+$result;
	}	
	fclose ($handle);
	if($sum=="0"){
		 echo "<h16><br>We can not match your keywords, please try with some other words or more words.</h16><br>";  
		 echo "<a href='u-step2.php'><h15>Please Click here to Step2</h15></a>";
   		 exit;  
	}
	$_SESSION['key1'] = mt_rand(1, 1000);
	$_SESSION["process"] = "success";
}
	
else if(isset($_SESSION["process"])){
	echo "<h15>You input have already been processed. You can continue to the next step. </h15><br>";
	echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";
}
else if(!isset($_SESSION["process"])){
	echo "<h16>Fail to process your request.</h16><br>";  
	echo "<a href='u-step2.php'><h15>Please Click here to the last step</h15></a>";
   	exit; 
}

echo "   <table class='table1'>
          <tr>
           <td width='10%'>
           <a href='u-parameter.php'><input type='button' name='next' class='btn primary' value='Continue' /></a></td>
          </tr> 
         </table> ";
echo " </div>
    </div>
</body>
</html>";
?>