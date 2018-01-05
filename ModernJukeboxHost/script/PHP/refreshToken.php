<?php
// is User logged in?
session_start();
if(!isset($_SESSION['userid'])) {
  die('Bitte zuerst <a href="login.php">einloggen</a>');
}

$pdo = new PDO('mysql:host=db6.variomedia.de;dbname=db26677', 'u26677', 'm89UDTTU');
$userid = $_SESSION['userid'];

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get Composer Packages -------------------------------------------------------

require '../../vendor/autoload.php';

$path = $_POST['path'];
$path = substr($path, 1, -1);

  $spotify = new SpotifyWebAPI\Session(
      '500ecf1e7acc47b7980a91efd66b9a9c',
      '9a3f95e414f2409f9c70490b199e521c',
      'http://localhost/ModernJukeboxHost/server/forwarding.php'
  );
  // Get refresh token from database
  $statement = $pdo->prepare("SELECT refreshToken FROM users WHERE id = :userid AND roomName = :path");
  $statement->execute(array('userid' => $userid, 'path' => $path));
  $result = $statement->fetch();
  $refreshToken = $result;

  $spotify->refreshAccessToken($refreshToken['refreshToken']);
  // get new access token
  $accessToken = $spotify->getAccessToken();
  // save access token in database
  $statement = $pdo->prepare("UPDATE users SET accessToken = :accessToken WHERE id = :id AND roomName = :path");
  $result = $statement->execute(array('accessToken' => $accessToken, 'id' => $userid, 'path' => $path));

  print_r($accessToken);
?>
