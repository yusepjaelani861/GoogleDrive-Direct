<?php
error_reporting(0);
function getDriveID($url){
    $filter1 = preg_match('/drive\.google\.com\/open\?id\=(.*)/', $url, $fileid1);
    $filter2 = preg_match('/drive\.google\.com\/file\/d\/(.*?)\//', $url, $fileid2);
    $filter3 = preg_match('/drive\.google\.com\/uc\?id\=(.*?)\&/', $url, $fileid3);
    if ($filter1) {
        $fileid = $fileid1[1];
    } else if ($filter2) {
        $fileid = $fileid2[1];
    } else if ($filter3) {
        $fileid = $fileid3[1];
    } else {
        $fileid = null;
    }
    
    return ($fileid);
}
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
	if($_POST['submit'] != ""){
		$url = $_POST['url'];
		$gid = getDriveID($url);
		$iframeid = my_simple_crypt($gid);
	}
?>
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
<link rel="stylesheet" href="https://rawcdn.githack.com/yusepjaelani861/Library-JS/e657f9b60072a7edf8be8f1eeb29f8042202a1cb/lib/style.css">
<meta name="referrer" content="no-referrer">
<link rel="shortcut icon" type="image/png" href="https://go.mitefansub.us/img/favicon.png" />
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
    </div></div>

    <div class="container">
<div class="left">
<div class="card">
<div class="card-head"><span>Link Download Generator</span> <div class="button-group" data_tab="link_generate"><a class="active" tab_select="batch_tab"><i class="material-icons">code</i></a></div></div>
<div class="card-body" tab_id="link_generate">
<div id="batch_tab" class="active">
<form action="" method="POST">
			<input type="text" size="80" name="url" value="<?php if($iframeid){echo $_POST['url'];}
			else{echo "";}?>"/>
			<button class="button" input type="submit" value="Generate" name="submit" >Generate </button>
		</form>
</div>
</div>
</div>
<div class="card" id="supported">
<div class="card-head">Supported File Host</div>
<div class="card-body">
<textarea class="form-control" readonly>
<?php if($iframeid){echo '/download/?id='.$iframeid.'</textarea>';?></textarea><br/>
		<center>
		<h2>Silahkan test link anda</h2>
		<a href="/download/?id=<?php echo $iframeid;?>"><button class="button">Download</button></a></center>
		<?php }?>
</textarea>
</div>
</div>
</div>
<div class="right">
<div class="card" id="howtouse">
<div class="card-head">How to use</div>
<div class="card-body">
<ol>
<li>Input your Original Link to link Generator</li>
<li>Select the link Type you want to Generate</li>
<li>Click Generate, done</li>
</ol>
</div>
</div>
</div>
</div>


<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous" type="6eb81c39c0396284308c29f0-text/javascript"></script>
<script src="https://rawgit.com/jackmoore/autosize/master/dist/autosize.min.js" type="6eb81c39c0396284308c29f0-text/javascript"></script>
<script src="https://rawcdn.githack.com/yusepjaelani861/Library-JS/e657f9b60072a7edf8be8f1eeb29f8042202a1cb/lib/script.js" type="6eb81c39c0396284308c29f0-text/javascript"></script>
</body>
</html>