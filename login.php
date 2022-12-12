<?php

session_start();
$baseUrl = getenv('BASE_URL');

$userName = getenv('USERNAME');
$password = getenv('PASSWORD');

if ($_POST['username']) {
    $postUser = escapeshellcmd($_POST['username']);
    $postPass = escapeshellcmd($_POST['password']);
    if (
        $postUser === escapeshellcmd($userName)
        && $postPass === escapeshellcmd( $password)
    ) {
        $_SESSION['loggedIn'] = true;
        header('Location: ' . $baseUrl);
    } else {
        $_SESSION['loggedIn'] = false;
        header('Location: '.$baseUrl.'/login' );
    }
    die();
}

echo getHtmlHeader();
getHtmlBodyAndTagsLogin($baseUrl);


function getHtmlBodyAndTagsLogin($baseUrl)
{
    echo getHtmlHeader() . '
<body>
<div class="container">
	<div class="row">
		<h1 class="h1"><a href="' . $baseUrl . '">Home</a></h1>
	</div>
	<form class="form-group" method="post" action="' . $baseUrl . '/login">
	  <input type="text" name="username" id="test" class="form-control" placeholder="test" aria-describedby="helpId">
	  <input type="password" name="password" id="test" class="form-control" placeholder="test" aria-describedby="helpId">
	  <button type="submit" >Login</button>
	</form>
	
</div>
</body>
</html>';
}
