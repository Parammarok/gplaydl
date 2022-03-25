<?php
include "apkparser.php";
set_time_limit(0);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
ignore_user_abort(true);
ini_set('default_socket_timeout', 6000);
//error_reporting(E_ALL);
ini_set("memory_limit", "4500M");
//include 'driveapi/streamnet@dmails.id.php';

 function readChunk($fp, $chunkSize)
{
    $ret = '';
    $bytesRemaining = $chunkSize;
    $bytesRead = 0;
    while (!feof($fp) && $bytesRemaining > 0) {
        $buffer = fread($fp, $bytesRemaining);
        $ret .= $buffer;
        $bytesRead += strlen($buffer);
        $bytesRemaining = $chunkSize - $bytesRead;
    }
    return $ret;
}  

$directoryName = 'files';

//Check if the directory already exists.
if(!is_dir($directoryName)){
    //Directory does not exist, so lets create it.
    mkdir($directoryName, 0777);
}


$id = $_GET['id'];
$dc = $_GET['dc'];// --device {$dc} 

if (isset($dc)) {
  $device = '--device '.$dc;
} else {
  $device = '--device bacon';
}



$dlcmd = "gplaydl download --packageId {$id} {$device} --ex n --splits n --path /var/www/clients/client0/web6/web/files";
$output = exec($dlcmd);
//echo $output;

$dlink = 'https://.$_SERVER['SERVER_NAME'].'/files/{$id}.apk';

$sapk = "files/{$id}.apk";
    $apkParser = new ApkParser();
    $apkParser->open($sapk);
	$packageid = $apkParser->getPackage();
    $vn = $apkParser->getVersionName();
    $vc = $apkParser->getVersionCode();
    $sdpi = $apkParser->getDpi();
   $minsdk = $apkParser->getminsdk();
 // echo $apkParser->getXML() . "\n";

//-----------------------------Google Drive---------------------------------------

$filesize = filesize($sapk); // bytes
$filesize = round($filesize / 1024 / 1024, 1); // megabytes with 1 digit

$created = time();
$filepath = "/files/{$id}.apk";
$Filename = $packageid.'_'.$vc.'_'.$created.'_appstia.com.apk';







//unlink($path);




$output = array(
    'response' =>'200',
	'packageid' =>$packageid , 
	'versionname' =>$vn ,
	'versioncode' =>$vc ,
	'dlink' =>$dlink  ,	
	'dpi' =>$sdpi,
	'minsdk' =>$minsdk,
	'Filesize' =>$filesize.' MB' ,
	'Filename' =>$Filename ,
	'Fileid' =>'',
	);


print_r(json_encode($output, JSON_UNESCAPED_SLASHES));








//header("location:".$path);
  //exit;
  
?>
