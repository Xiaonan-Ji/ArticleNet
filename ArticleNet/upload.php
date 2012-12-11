<?php
session_start();
error_reporting(E_ALL|E_STRICT);

echo "
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
  </head>

<body>

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
          <li><a href='u-step1.php'>Run Your Own SR</a></li>
          <li class='active'><a href='step1.php'>Evaluate DERP Models</a></li>
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
        echo "okay: logged in...\n";
    }
}

  $dir_base="/var/www/cgi-bin/SR/SR-C/data/";
 if ($_FILES['file']['type'] != "text/plain") {
          echo "<p> Please upload your file in txt format.</p>";
     } 
 else {
    if ($_FILES["file"]["error"] > 0)
    {
      echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
    else
    {
    echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    echo "Type: " . $_FILES["file"]["type"] . "<br />";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
    echo  "Server name is: " . $_SERVER['SERVER_NAME']. "<br />";
    echo "Server admin is: " . $_SERVER['SERVER_ADMIN']."<br />";
    echo "doc root is: " . $_SERVER['DOCUMENT_ROOT']."<br />";
    echo "php functions is: " . $_SERVER['PHP_SELF']. "<br />";

       echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";


//display the file
      if (file_exists('./files/'. $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
      else
      {
	   if(is_uploaded_file($_FILES["file"]["tmp_name"])) {
		   echo "file has been successfully uploaded! <br />";
		   echo "Displaying contents\n <br />";
   		   //readfile($_FILES["file"]["tmp_name"]); 
		}
	    $tmp_name = $_FILES["file"]["tmp_name"];
            $name = $_FILES["file"]["name"];     

       echo " <hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'> ";

// read in system log counter
	$file1 =  $dir_base . "log.txt";	
	$handle = fopen ($file1, "r");
	$string = fgets($handle, 4096);
	$counter = intval($string);
	fclose ($handle);

   // make the new folder
	$cmd1 = "mkdir /var/www/cgi-bin/SR/SR-C/data/" .  $counter . $name . "data 0777";

        if (!($stream = ssh2_exec($con, $cmd1))) {
            echo "fail: unable to execute command\n";
        } 
		else {
			echo "folder have been made...\n";
			echo '<br />' ;
            // collect returning data from command
            stream_set_blocking($stream, true);
            $data = "";
            while ($buf = fread($stream,4096)) {
                $data .= $buf;
            }
            fclose($stream);
        }

	   //mkdir("$dir_base$name",7777);
	   $final_dir = "/var/www/cgi-bin/SR/SR-C/data/" .  $counter . $name . "data/";
	   //system("chmod 777 $final_dir");
	   $_SESSION["dir"] = $final_dir;

//update counter
		$cmd4 = "true >" . $file1;
        if (!($stream = ssh2_exec($con, $cmd4))) {
            echo "fail: unable to execute command\n";
        } 
		else {
			echo "updating the counter...\n";
			echo '<br />' ;
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
			echo "permission has be granted...\n";
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
	  echo "file has been stored...\n";
          echo 'stored in: ',$final_dir;
		  echo '<br />';
		  //echo 'click <a href="paper.html">here</a> to return back<br />';
	  }
	else{
		echo "Could not move uploaded file $tmp_name to $dir_base";
		die;
		//throw new Exception("Could not move uploaded file $tmp_name to $dir_base");
                }


// call parser
	$cmd2 = "/var/www/cgi-bin/SR/SR-C/preprocess " .  $final_dir.$name . " 1 " . $final_dir;
        if (!($stream = ssh2_exec($con, $cmd2 ))) {
            echo "fail: unable to execute command\n";
        } 
		else {
			echo "parser the article list...\n";
            // collect returning data from command
            stream_set_blocking($stream, true);
            $data = "";
            while ($buf = fread($stream,4096)) {
                $data .= $buf;
            }
            fclose($stream);
	    echo "all done...!";
        }

	   
	   // echo "<br />";
		//$args="/var/www/cgi-bin/SR/SR-C/data/I-list 1";
		//if($args == "")
		//echo "<h1>You didn't enter any arguments.</h1>";
		//else
		//{
		//	echo "<h5>Processing your request...</h5>";
		//	$command = "sudo "."/var/www/cgi-bin/SR/SR-C/parser " . escapeshellcmd($args);
		//	passthru($command,$res);
		//	echo "<h4>".$res."</h4>";
		//}
	   
      }
    }
  }
  echo "   <table class='table1'>
          <tr>
           <td width='10%'>
           <a href='step2.html'><input type='button' name='next' class='btn primary' value='Continue to Step2' /></a></td>
           <td width='10%'>
           <a href='step1.php'><input type='button' name='back' class='btn default' value='Return Back' /></a></td>
          </tr> 
         </table> ";
		 
  echo " </div>
         </div>
	</body>
	</html>";
  
?>