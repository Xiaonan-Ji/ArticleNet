<?php
session_start();

echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <title>Step1-Upload</title>
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
			<div style='position:absolute;left:720px;top:88px'><h18>Version1.2. &nbsp Last modified: 12/19/2012.</h18></div>
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
			<li><a href='step1.php'>EEvaluate DERP Models</a></li>
		</ul>
		</div>
       
		<div class='hero-unit2'>
     ";

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
        //echo "okay: logged in...\n";
    }
}

$dir_base_log="/var/www/cgi-bin/SR/SR-C/";
$dir_base="/var/www/cgi-bin/SR/SR-C/data/";
$file_size_max = 20480000;

if(!isset($_FILES["file"])){
	echo "<h16>Sorry we could not find your workspace/session, please start over. </h16><br>";
	echo "<a href='index.html'><h15>Please Click here to Homepage</h15></a>";
	exit;
}

if(isset($_POST['submit']) && $_POST['key1'] == $_SESSION['key1']){
$_SESSION["tag"]="-1";
if(!isset($_FILES["file"])){
	echo "<h16>Please uoplad your article list first. </h16><br>";
	echo "<a href='u-step1.php'><h15>Please Click here to Step1</h15></a>";
	exit;
}

 if (($_FILES['file']['type'] != "text/plain")) {
          echo "<h16>Please upload your file in txt format and no larger than 20MB.</h16><br>";
	  echo "<a href='u-step1.php'><h15>Please Click here to Step1</h15></a>";
	  exit;
     } 
 else {
    if ($_FILES["file"]["error"] > 0)
    {
      echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
    else
    {
	echo "<h4>Uploading your article list...</h4>";
    echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    echo "Type: " . $_FILES["file"]["type"] . "<br />";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " KB<br />";
    //echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
    //echo  "Server name is: " . $_SERVER['SERVER_NAME']. "<br />";
    //echo "Server admin is: " . $_SERVER['SERVER_ADMIN']."<br />";
    //echo "doc root is: " . $_SERVER['DOCUMENT_ROOT']."<br />";
    //echo "php functions is: " . $_SERVER['PHP_SELF']. "<br />";

    echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";

//display the file
     if ($_FILES["file"]["size"] > $file_size_max) {  
   	 echo "<h16>Please upload a file no larger than 2000KB.</h16>";  
	 echo "<a href='u-step1.php'><h15>Please Click here to Step1</h15></a>";
   	 exit;  
	} 
      else
      {
	   if(is_uploaded_file($_FILES["file"]["tmp_name"])) {
		   echo "Your article list has been successfully uploaded! <br />";
		   //echo "Displaying contents\n <br />";
   		   //readfile($_FILES["file"]["tmp_name"]); 
		}
	    $tmp_name = $_FILES["file"]["tmp_name"];
            $name = $_FILES["file"]["name"];     

       //echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";

// read in system log counter
	$file1 =  $dir_base_log . "log.txt";	
	$handle = fopen ($file1, "r");
	$string = fgets($handle, 4096);
	$counter = intval($string);
	fclose ($handle);

// make the new folder
	//echo "mkdir /var/www/cgi-bin/SR/SR-C/data/" . $counter .  $name . "data 0777";
	$cmd1 = "mkdir /var/www/cgi-bin/SR/SR-C/data/" . $counter .  $name . "data 0777";

        if (!($stream = ssh2_exec($con, $cmd1))) {
            echo "fail: unable to execute command\n";
        } 
		else {
			//echo "folder have been made...\n";
			//echo '<br />' ;
            // collect returning data from command
            stream_set_blocking($stream, true);
            $data = "";
            while ($buf = fread($stream,4096)) {
                $data .= $buf;
            }
            fclose($stream);
        }

	   //mkdir("$dir_base$name",7777);
	   $final_dir = "/var/www/cgi-bin/SR/SR-C/data/" . $counter . $name . "data/";
	   //system("chmod 777 $final_dir");
	   $_SESSION["dir"] = $final_dir;

//update counter
	$cmd4 = "true >" . $file1;
        if (!($stream = ssh2_exec($con, $cmd4))) {
            echo "fail: unable to execute command\n";
        } 
		else {
			echo "Updating the counter...\n";
			//echo '<br />' ;
            // collect returning data from command
            stream_set_blocking($stream, true);
            $data = "";
            while ($buf = fread($stream,4096)) {
                $data .= $buf;
            }
            fclose($stream);
        }
		
	$counter = $counter+1;
	$cmd5 = "echo " . $counter . " >>" . $file1;
        if (!($stream = ssh2_exec($con, $cmd5))) {
            echo "fail: unable to execute command\n";
        } 
		else {
			echo "done!\n";
			echo '<br />' ;
            // collect returning data from command
            stream_set_blocking($stream, true);
            $data = "";
            while ($buf = fread($stream,4096)) {
                $data .= $buf;
            }
            fclose($stream);
        }

// give the permission
	//$old = umask(0);
	$cmd3 = "chmod 777 " . $final_dir; 
        if (!($stream = ssh2_exec($con, $cmd3))) {
            echo "fail: unable to execute command\n";
        } 
		else {
			echo "Permission has be granted.\n";
			echo '<br />' ;
            // collect returning data from command
            stream_set_blocking($stream, true);
            $data = "";
            while ($buf = fread($stream,4096)) {
                $data .= $buf;
            }
            fclose($stream);
        }
	//umask($old); 


   // move and store the file
      if(move_uploaded_file($tmp_name,$final_dir.$name)){
	  echo "File has been stored.\n";
          //echo 'stored in: ',$final_dir;
	echo '<br />';
	//echo 'click <a href="paper.html">here</a> to return back<br />';
	  }
		else{
		echo "Could not move uploaded file $tmp_name to $dir_base";
		die;
		//throw new Exception("Could not move uploaded file $tmp_name to $dir_base");
                }


// call pre-processing
	$cmd2 = "/var/www/cgi-bin/SR/SR-C/preprocess " .  $final_dir.$name . " 1 " . $final_dir;
        if (!($stream = ssh2_exec($con, $cmd2 ))) {
            echo "fail: unable to execute command\n";
        } 
		else {
			echo "Preprocessing the article list...\n";
            // collect returning data from command
            stream_set_blocking($stream, true);
            $data = "";
            while ($buf = fread($stream,4096)) {
                $data .= $buf;
            }
            fclose($stream);
	    echo "all done!";
        }

// get the numbet of articles, and check the total number and format

	$PATH=$final_dir;
	$num=sizeof(scandir($PATH));
	$num=($num>2)?$num-2:0;
	$articlenum = $num -1;
	$_SESSION["articlenum"]=$articlenum;
	echo "<br>";
	echo "There are " . $articlenum. " articles in total.";

	if($articlenum>4000){
		$cmd3 = "rm -rf " . $final_dir;
       		 if (!($stream = ssh2_exec($con, $cmd3 ))) {
         		  	 echo "fail: unable to execute command\n";
      	 	 } 
		else {
		  	 echo "delete the invalid upload...\n<br>";
        			 // collect returning data from command
          		 	 stream_set_blocking($stream, true);
          		 	 $data = "";
         		  	 while ($buf = fread($stream,4096)) {
              			 	 $data .= $buf;
         		 	  }
          		 	 fclose($stream);
     		   }

		 echo "<h16>Please upload a list no more than 4000 articles.</h16>";  
		 echo "<a href='u-step1.php'><h15>Please Click here to Step1</h15></a>";
   		 exit;  
	}

	if($articlenum<2){
		$cmd4 = "rm -rf " . $final_dir;
       		 if (!($stream = ssh2_exec($con, $cmd4 ))) {
         		   echo "fail: unable to execute command\n";
      	 	 } 
		else {
		   echo "delete the invalid upload...\n<br>";
        		 // collect returning data from command
          		  stream_set_blocking($stream, true);
          		  $data = "";
         		   while ($buf = fread($stream,4096)) {
              			  $data .= $buf;
         		   }
          		  fclose($stream);
     		 }

		 echo "<h16>Please upload in MEDLINE format.</h16>";  
		 echo "<a href='u-step1.php'><h15>Please Click here to Step1</h15></a>";
   		 exit;  
		}

      }
    }
  }
	$_SESSION['key1'] = mt_rand(1, 1000);
	$_SESSION["upload"]="success";
 }
 else if(isset($_SESSION["upload"])){
	echo "<h15>You have already uploaded your article list. You can continue to the next step. </h15><br>";
	echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";
}
else if(!isset($_SESSION["upload"])){
	 echo "<h16>Please upload your article list in right format.</h16>";  
	echo "<a href='u-step1.php'><h15>Please Click here to Step1</h15></a>";
   	exit;  
}
	
echo "  	 <table class='table1'>
				<tr>
				<td width='10%'>
				<a href='u-step2.php'><input type='button' name='next' class='btn primary' value='Continue to Step2' /></a></td>
				</tr> 
			</table> ";
			echo " </div>
	</div>
</body>
</html>";
?>