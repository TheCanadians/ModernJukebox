<?php
// is User logged in?
session_start();
if(!isset($_SESSION['userid'])) {
  die('Bitte zuerst <a href="login.php">einloggen</a>');
}

$pdo = new PDO('mysql:host=localhost;dbname=modernjukeboxhost', 'root', '');
$userid = $_SESSION['userid'];

ini_set('max_execution_time', 3000);

// Get Composer Packages -------------------------------------------------------

require '../../vendor/autoload.php';

// Spotify Stuff ---------------------------------------------------------------
// Get access token from database
$statement = $pdo->prepare("SELECT accessToken FROM users WHERE id = $userid");
$statement->execute();
$result = $statement->fetch();
$accessToken = $result;


$api = new SpotifyWebAPI\SpotifyWebAPI();
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


}

if ($_POST['firstID'] != null) {
  $firstID = $_POST['firstID'];
  $secondID = $_POST['secondID'];
  if($secondID == null) {
    fillPlaylist($firstID);
  }
  else {
    fillPlaylist($firstID, $secondID);
  }
}

function fillPlaylist ($first, $second = null) {
  if ($second != null) {
    $GLOBALS['api']->replaceUserPlaylistTracks('guildwhoops', '6bQ7gg4w5uTvnVatgtNitu', [
      'spotify:track:' . $first,
      'spotify:track:' . $second
    ]);
    playPlaylist();
  }
  else {
    $playlistTracks = $GLOBALS['api']->getUserPlaylistTracks('guildwhoops', '6bQ7gg4w5uTvnVatgtNitu');
    $deleteID = [
      ['id' => $playlistTracks->items[0]->id]
    ];
    $GLOBALS['api']->deleteUserPlaylistTracks('guildwhoops', '6bQ7gg4w5uTvnVatgtNitu', $deleteID);
    //somehow get new "first" song from queue and add to spotify playlist
    $dom = new DOMDocument();
    $dom->loadHTML("homeJS.php");
    $dom->validate();
    $queue = $dom->getElementById('queue');
    $queue->getElementsByTagName('div');
    $firstID = $queue[0]->getAttribute('id');

    $GLOBALS['api']->addUserPlaylistTracks('guildwhoops', '6bQ7gg4w5uTvnVatgtNitu', [
      $first
    ]);
  }
}

function playPlaylist() {
  try {
    $playlists = $GLOBALS['api']->getUserPlaylists($GLOBALS['api']->me()->id, [
        'limit' => 1
    ]);
    $context_uri = $playlists->items[0]->uri;
    $devices = $GLOBALS['api']->getMyDevices();
    $GLOBALS['api']->play($devices->devices[0]->id, [
      'context_uri' => $context_uri,
    ]);
    $lastResponse = $GLOBALS['api']->getLastResponse();
    if ($lastResponse['status'] == 202) {
      print_r($lastResponse);
      sleep(5);
      playPlaylist();
    }
    else {
      sleep(1);
      $current = $GLOBALS['api']->getMyCurrentTrack();
      checkTime($current->item->duration_ms);
    }
  }
  catch (Exception $e) {
    echo $e;
  }
}

function checkTime($length) {
  sleep (($length - 1000) / 1000);
  print_r("Delete And add Song");
}
?>
