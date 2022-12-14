<?php

session_start();
$baseUrl = getenv('BASE_URL');
$baseUrlIp = 'http://' . $_SERVER["SERVER_NAME"] . ':' . $_SERVER["SERVER_PORT"];

checkTheAllowedUri($baseUrl);
login();

if ($_GET['printtoken']) {
    printToken(escapeshellcmd(escapeshellarg($_GET['printtoken'])));
} elseif ($_GET['gen']) {
    gen(escapeshellarg($_GET['tokenName']));
} elseif ($_GET['delete']) {
    delete(escapeshellcmd(escapeshellarg($_GET['delete'])));
} else {
    getHtmlBodyAndTags($baseUrl, $baseUrlIp);
}

function loggedIn()
{
    return $_SESSION['loggedIn'];
}

function login()
{
    if (!loggedIn()) {
        require_once 'login.php';
        die();
    }
}


function printToken($id)
{
    $output = shell_exec('cd ../ && /app/VpnHoodServer print ' . $id);
    echo getToken($output);
}

function gen($name = 'Reza Server')
{
    $output = shell_exec('cd ../ && /app/VpnHoodServer gen -name="' . $name . '"');
    echo getToken($output);
}

function delete($id)
{
    echo shell_exec('rm ../storage/access/' . $id . '*');
}

function getToken($output)
{
    $start = strpos($output, 'vh://');
    $token = substr($output, $start);
    $end = strpos($token, '---');
    $endOfString = substr($token, $end);
    $token = str_replace($endOfString, '', $token);
    return trim($token);
}

function listFilesSortedByDate($dir)
{
    $ignored = array('.', '..', '.svn', '.htaccess');

    $files = array();
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) {
            continue;
        }
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}

function getTokenInfo(string $dir, $id)
{
    $tokenContent = file_get_contents($dir . $id . '.token');
    $usageContent = file_get_contents($dir . $id . '.usage');
    $tokenInfo = json_decode($tokenContent, true);
    $usageInfo = json_decode($usageContent, true);
    $tokenName = $tokenInfo['Token']['name'] ?? 'NO NAME';
    return [
        'name' => $tokenName,
        'upload' => humanFileSize($usageInfo['SentTraffic']),
        'download' => humanFileSize($usageInfo['ReceivedTraffic']),
    ];
}

function humanFileSize($size, $unit = "")
{
    if ((!$unit && $size >= 1 << 30) || $unit == "GB") {
        return number_format($size / (1 << 30), 2) . "GB";
    }
    if ((!$unit && $size >= 1 << 20) || $unit == "MB") {
        return number_format($size / (1 << 20), 2) . "MB";
    }
    if ((!$unit && $size >= 1 << 10) || $unit == "KB") {
        return number_format($size / (1 << 10), 2) . "KB";
    }
    return number_format($size) . " bytes";
}

function getBootstrapCard($tokenInfo, $id)
{
    return '<div class="card">
				<div class="card-body">
					<h5 class="card-title">' . $tokenInfo['name'] . '</h5>
					<p class="card-text">' . $id . '</p>
					<p class="card-text">Downloaded: <strong>' . $tokenInfo['download'] . '</strong> Uploaded: <strong>' . $tokenInfo['upload'] . '</strong></p>
					<p class="card-text">' . $id . '</p>
					<a href="?printtoken=' . $id . '" class="btn btn-primary" onclick="getToken(\'' . $id . '\')">Show Token</a>
					<a href="?delete=' . $id . '" class="btn " onclick="deleteToken(\'' . $id . '\')"><i class="bi bi-trash text-danger"></i></a>
					<div class="card-text" >
					    <div class="spinner-border text-primary d-none" id="' . $id . '_spinner" role="status">
                            <span class="sr-only"></span>
                        </div>
					    <p class="card-text d-none" id="' . $id . '" ></p>
					    <a class="btn btn-info d-none" onclick="copyText(\'' . $id . '\')" id="' . $id . '_cpbtn"> Copy To Clipboard</a>
                    </div>
				</div>
			</div>';
}

function showCardsForTokens()
{
    $dir = '../storage/access/';
    $files = listFilesSortedByDate($dir);
    foreach ($files as $file) {
        if (is_file($dir . $file) && strpos($file, '.token') !== false) {
            $id = str_replace('.token', '', $file);
            $tokenInfo = getTokenInfo($dir, $id);
            $output .= getBootstrapCard($tokenInfo, $id);
        }
    }
    return $output ?? '';
}

function getJsContent()
{
    return file_get_contents('app.js');
}

function getHtmlHeader()
{
    return '<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
	      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css"
	      integrity="sha384-b6lVK+yci+bfDmaY1u0zE8YYJt0TZxLEAFyYSLHId4xoVvsrQu3INevFKo+Xir8e" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
	        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
	        crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
	        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
	        crossorigin="anonymous"></script>
	<title>HoodVPN TokenManager</title>
</head>';
}

function getGenerateButton()
{
    return '<div class="row">
		<form class="row g-3" action="?gen=1" METHOD="get">
			<div class="col-auto">
				<label for="tokenName" class="visually-hidden">Give a Token Name</label>
				<input name="tokenName" type="text" class="form-control" id="tokenName" placeholder="Token Name">
			</div>
			<div class="col-auto">
				<button type="submit" class="btn btn-primary mb-3" onclick="generateToken(\'new_code\')">Generate Token</button>
				<div class="card-text" >
					    <div class="spinner-border text-primary d-none" id="new_code_spinner" role="status">
                            <span class="sr-only"></span>
                        </div>
					    <p class="card-text d-none" id="new_code" ></p>
					    <a class="btn btn-info d-none" onclick="copyText(\'new_code\')" id="new_code_cpbtn"> Copy To Clipboard</a>
                </div>
			</div>
		</form>
	</div>';
}

function getHtmlBodyAndTags($baseUrl, $baseUrlIp)
{
    echo getHtmlHeader() . '
<body>
<div class="container">
	<div class="row">
		<h1 class="h1"><a href="' . $baseUrl . '">Home</a></h1>
	</div>
	' . getGenerateButton() . '
	<div class="row">
		<div class="col-sm-6">
			' . showCardsForTokens() . '
		</div>
	</div>
</div>
<script>
    var baseUrlAll = "' . $baseUrlIp . $baseUrl . '";
	' . getJsContent() . '
</script>
</body>
</html>';
}

function checkTheAllowedUri($baseUrl)
{
    $queryString = $_SERVER["QUERY_STRING"] ? '?' . $_SERVER["QUERY_STRING"] : '';
    $allowedUri = $baseUrl . $queryString;
    if ($_SERVER['REQUEST_URI'] === $baseUrl.'/logout'){
        $_SESSION['loggedIn']=false;
        echo 'loged out';
    }
    if ($_SERVER['REQUEST_URI'] !== $allowedUri
        && $_SERVER['REQUEST_URI']!== $baseUrl.'/login'
    ) {
        header('Location: ');
        die();
    }

}
