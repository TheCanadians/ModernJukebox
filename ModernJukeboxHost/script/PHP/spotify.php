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
$accessToken = $result;

$api = new SpotifyWebAPI\SpotifyWebAPI();
//$api->setAccessToken($accessToken);

try {
  $api->setAccessToken($accessToken['accessToken']);
  $test = $api->getTrack('4ig5yrQLjlT10HzZDPV1cG');
} catch (Exception $e) {
  $spotify = new SpotifyWebAPI\Session(
      '500ecf1e7acc47b7980a91efd66b9a9c',
      '9a3f95e414f2409f9c70490b199e521c',
      'http://localhost/ModernJukeboxHost/server/forwarding.php'
  );
  // Get refresh token from database
  $statement = $pdo->prepare("SELECT refreshToken FROM users WHERE id = $userid");
  $statement->execute();
  $result = $statement->fetch();
  $refreshToken = $result;

  $spotify->refreshAccessToken($refreshToken['refreshToken']);

  $accessToken = $spotify->getAccessToken();
  $api->setAccessToken($accessToken);

  $test = $api->getTrack('4ig5yrQLjlT10HzZDPV1cG');

  try{
    /*
    $wasPaused = $api->play(false, [
      'uris' => ['spotify:track:0tgVpDi06FyKpA1z0VMD4v'],
    ]);

    $lastResponse = $api->getLastResponse();
    */
    //print_r($lastResponse);
  }
  catch (Exception $e) {
    //print_r($e);
  }
}

?>
