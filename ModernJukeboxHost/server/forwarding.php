<?php
session_start();
$pdo = new PDO('mysql:host=db6.variomedia.de;dbname=db26677', 'u26677', 'm89UDTTU');

$userid = $_SESSION['userid'];

require '../vendor/autoload.php';

$spotify = new SpotifyWebAPI\Session(
    '500ecf1e7acc47b7980a91efd66b9a9c',
    '9a3f95e414f2409f9c70490b199e521c',
    'http://dennisschmidt.net/jukebox/server/forwarding.php'
);

// Request a access token using the code from Spotify
$spotify->requestAccessToken($_GET['code']);

$accessToken = $spotify->getAccessToken();
$refreshToken = $spotify->getRefreshToken();

// Save access and refresh token in database
$statement = $pdo->prepare("UPDATE users SET accessToken = :accessToken, refreshToken = :refreshToken WHERE id = :userid");
$result = $statement->execute(array('accessToken' => $accessToken, 'refreshToken' => $refreshToken, 'userid' => $userid));

header('Location: homeJS.php');
die();

?>
