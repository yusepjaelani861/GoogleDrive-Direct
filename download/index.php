<?php
function my_simple_crypt( $string, $action = 'e' ) {
  	$secret_key = 'drivekey';
  	$secret_iv = 'google';
  	$output = false;
  	$encrypt_method = "AES-256-CBC";
  	$key = hash( 'sha256', $secret_key );
  	$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
  	if( $action == 'e' ) {
    		$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
  		}else if( $action == 'd' ){
    			$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
  	}
  	return $output;
}
function getDownload($id){
		$ch = curl_init("https://drive.google.com/uc?id=$id&authuser=0&export=download");
		curl_setopt_array($ch, array(
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_POSTFIELDS => [],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => 'gzip,deflate',
			CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
			CURLOPT_HTTPHEADER => [
				'accept-encoding: gzip, deflate, br',
				'content-length: 0',
				'content-type: application/x-www-form-urlencoded;charset=UTF-8',
				'origin: https://drive.google.com',
				'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
				'x-client-data: CKG1yQEIkbbJAQiitskBCMS2yQEIqZ3KAQioo8oBGLeYygE=',
				'x-drive-first-party: DriveWebUi',
				'x-json-requested: true'
			]
		));
		$response = curl_exec($ch);
		$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($response_code == '200') { // Jika response status OK
			$object = json_decode(str_replace(')]}\'', '', $response));
			if(isset($object->downloadUrl)) {
				return $object->downloadUrl;
			} 
		} else {
			return $response_code;
		}
}
if($_GET['id'] != ""){
	$gid = $_GET['id'];
	$original_id = my_simple_crypt($gid, 'd');
	$url = "https://www.googleapis.com/drive/v2/files/$original_id?supportsTeamDrives=true&key=AIzaSyAgwkEFJ-iJMP-rYcMtVCb-bD1FPMdNyRk";
	$json = file_get_contents($url);
	$json_data = json_decode($json, true);
}?>
<!DOCTYPE html>
<html>
<head>
<?php
    error_reporting(0);
    	if($_POST['asdf']){
		$id = $_POST['asdf'];
		$original_id = my_simple_crypt($id, 'd');
		$docsurl = getDownload($original_id);
		echo "<meta http-equiv='refresh' content='0;url=$docsurl'>\n"; 
		}?>
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title><?php echo $json_data["title"]; ?> - Google Drive</title>
<link rel="stylesheet" href="/lib/style.css">
<meta name="referrer" content="no-referrer">
<link rel="shortcut icon" type="image/png" href="favicon.png" />
<meta name="robots" content="noindex" />
<link href="https://fonts.googleapis.com/css?family=Montserrat:300,600&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
<div class="notification"></div>
<div class="navbar">
<div class="container">
<div id="nav_toggle"><i class="material-icons">menu</i></div>
<div class="brand"><a href="/">Google Drive</a></div>
<nav id="navbar">
<ul>
<li><a href="/">Home</a></li>
</ul>
</nav>
    </div></div><div class="container">
<div class="my1 mx-auto text-center">
</div>
<div class="card medium center">
<div class="card-head center">Google Drive</div>
<ul class="list-1">
<li><b>Filename:</b> <span><?php echo $json_data["title"]; ?></span></li>
<li><b>Filesize:</b> <span><?php echo $json_data["fileSize"]; ?> KB</span></li>
</ul>
<div class="card-body center">
<p><form method="post" action="">
<input type="hidden" name="asdf" value="<?php echo $gid;?>">
                        <button class="button">DOWNLOAD</button>
                        </form></p>
</div>
</div>
<div class="my1 mx-auto text-center">
</div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous" type="6eb81c39c0396284308c29f0-text/javascript"></script>
<script src="https://rawgit.com/jackmoore/autosize/master/dist/autosize.min.js" type="6eb81c39c0396284308c29f0-text/javascript"></script>
<script src="https://rawcdn.githack.com/yusepjaelani861/Library-JS/e657f9b60072a7edf8be8f1eeb29f8042202a1cb/lib/script.js" type="6eb81c39c0396284308c29f0-text/javascript"></script>
</body>
</html>
