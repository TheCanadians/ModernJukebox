<?php
//==============================================================================
// Spotify

require_once '../vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
  '500ecf1e7acc47b7980a91efd66b9a9c',
  '9a3f95e414f2409f9c70490b199e521c',
  'http://localhost/ModernJukeboxHost/server/forwarding.php'
);

$options = [
  'scope' => [
    'user-library-modify',
    'playlist-read-private',
    'playlist-read-collaborative',
    'playlist-modify-public',
    'playlist-modify-private',
    'user-read-playback-state',
    'user-modify-playback-state',
    'user-read-currently-playing',
    'user-read-recently-played',
  ],
];

header('Location: ' . $session->getAuthorizeUrl($options));
die();

 ?>
