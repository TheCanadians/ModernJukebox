<?php
$pdo = new PDO('mysql:host=db6.variomedia.de;dbname=db26677', 'u26677', 'm89UDTTU');
$userid = $_SESSION['userid'];

// Get Composer Packages -------------------------------------------------------

require '../vendor/autoload.php';

// Spotify Stuff ---------------------------------------------------------------
// Get access token from database
$statement = $pdo->prepare("SELECT accessToken FROM users WHERE id = $userid");
$statement->execute();
$result = $statement->fetch();
$accessToken = json_encode($result);

// Get refresh token from database
$statement = $pdo->prepare("SELECT refreshToken FROM users WHERE id = $userid");
$statement->execute();
$result = $statement->fetch();
$refreshToken = json_encode($result);

$clientID = '500ecf1e7acc47b7980a91efd66b9a9c';
$clientSecret = '9a3f95e414f2409f9c70490b199e521c';
$cliID = json_encode($clientID);
$cliSecret = json_encode($clientSecret);
?>
