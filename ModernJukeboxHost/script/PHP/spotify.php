<?php
$pdo = new PDO('mysql:host=localhost;dbname=modernjukeboxhost', 'root', '');
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

?>
