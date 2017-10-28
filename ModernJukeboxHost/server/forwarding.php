<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=modernjukeboxhost', 'root', '');

$userid = $_SESSION['userid'];

require '../vendor/autoload.php';

$spotify = new SpotifyWebAPI\Session(
    '500ecf1e7acc47b7980a91efd66b9a9c',
    '9a3f95e414f2409f9c70490b199e521c',
    'http://localhost/ModernJukeboxHost/server/forwarding.php'
);

// Request a access token using the code from Spotify
$spotify->requestAccessToken($_GET['code']);

$accessToken = $spotify->getAccessToken();

$statement = $pdo->prepare("UPDATE users SET accessToken = :accessToken WHERE id = :userid");
$result = $statement->execute(array('accessToken' => $accessToken, 'userid' => $userid));

header('Location: home.php');
die();

?>
