<?php
// is User logged in?
session_start();
if(!isset($_SESSION['userid'])) {
  die('Bitte zuerst <a href="login.php">einloggen</a>');
}

$pdo = new PDO('mysql:host=localhost;dbname=modernjukeboxhost', 'root', '');
$userid = $_SESSION['userid'];

// Get Composer Packages -------------------------------------------------------

require '../vendor/autoload.php';

// Spotify Stuff ---------------------------------------------------------------
// Get access token from database
$statement = $pdo->prepare("SELECT accessToken FROM users WHERE id = $userid");
$statement->execute();
$result = $statement->fetch();
$accessToken = $result;


$api = new SpotifyWebAPI\SpotifyWebAPI();
$api->setAccessToken($accessToken);

$play = $_POST['PlayID'];
$pause = $_POST['PauseID'];

if ($_POST['PlayID'] != null) {
  $api->play(false, [
    'uris' => ['https://api.spotify.com/v1/tracks/' . $play],
  ]);
}

if ($_POST['PauseID'] != null) {
  $api->pause();
}

?>
