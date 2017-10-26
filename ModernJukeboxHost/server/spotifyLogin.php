<?php
//==============================================================================
// Spotify

require_once '../vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
  '500ecf1e7acc47b7980a91efd66b9a9c',
  '9a3f95e414f2409f9c70490b199e521c',
  'http://localhost/ModernJukeboxHost/server/home.php'
);

header('Location: ' . $session->getAuthorizeUrl());
die();

 ?>
