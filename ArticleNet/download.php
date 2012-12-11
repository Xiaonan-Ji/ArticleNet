<?php   
$dir_base="/var/www/cgi-bin/SR/SR-C/system/ResearchModel/";
$file_name=$_GET['download_file'];
$fullPath= $dir_base.$file_name;
header("Expires: 0");   
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");   
header("Cache-Control: no-store, no-cache, must-revalidate");   
header("Cache-Control: post-check=0, pre-check=0", false);   
header("Pragma: no-cache");   
header("Content-type: application/pdf");   
// tell file size   
header('Content-length: '.filesize($dir_base.$file_name));   
// set file name   
header('Content-disposition: attachment; filename='.basename($dir_base.$file_name));   
readfile($dir_base.$file_name);   
// Exit script. So that no useless data is output-ed.   
exit;   
?>  
