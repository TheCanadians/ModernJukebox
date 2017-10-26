<?php
// is User logged in?
session_start();
if(!isset($_SESSION['userid'])) {
  die('Bitte zuerst <a href="login.php">einloggen</a>');
}

$pdo = new PDO('mysql:host=localhost;dbname=modernjukeboxhost', 'root', '');
$userid = $_SESSION['userid'];

//echo "Hallo User: ".$userid;

// Spotify Stuff ---------------------------------------------------------------

require '../vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    '500ecf1e7acc47b7980a91efd66b9a9c',
    '9a3f95e414f2409f9c70490b199e521c',
    'http://localhost/ModernJukeboxHost/server/home.php'
);

// Request a access token using the code from Spotify
$session->requestAccessToken($_GET['code']);

$accessToken = $session->getAccessToken();

//echo $accessToken;

$api = new SpotifyWebAPI\SpotifyWebAPI();
$api->setAccessToken($accessToken);

// Firebase Stuff --------------------------------------------------------------

const DEFAULT_URL = 'https://modern-jukebox.firebaseio.com';
const DEFAULT_TOKEN = 'oi5JKlnTahYEyORi9YvlvY36pn9nxByDJjx0FX0j';
const DEFAULT_PATH = '/';

$path = '';

$firebase = new \Firebase\FirebaseLib(DEFAULT_URL, DEFAULT_TOKEN);

 ?>

<html !DOCTYPE>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modern Jukebox Host - Home</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container col-md-12" style="padding: 0;">
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="#">ModernJukebox Host</a>
      </div>
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="newRestaurant.php"><span class="glyphicon glyphicon-plus"></span> Add New Room</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </nav>
  <!-- Left side -->
  <div class="col-md-4" style="height: 100%;">
    <!-- Restaurant Info -->
    <div class="col-md-12">
      <?php

              $statement = $pdo->prepare("SELECT roomName FROM users WHERE id = $userid");
              $statement->execute();
              $result = $statement->fetch();
              $path = DEFAULT_PATH . $result[0];
      ?>
      <select class="form-control">
        <!-- Get restaurants from Database -->
        <?php
        $i = 1;

        // some kind of foreach loop that loops through the Database
        //foreach () {}
        $name = trim($firebase->get($path . "/name"), '""');
        echo '<option value="'.$i.'">'.$name.'</option>';
        ?>
      </select>
      <!-- Restaurant Info Formular -->
      <form>
        <div class="form-group">
          <label for="maxQ">Max Queue: </label>
          <input type="text" id="maxQ" class="form-control" value=<?php echo $firebase->get($path . '/maxQueue'); ?>>
        </div>
        <div class="form-group">
          <label for="maxSpU">Max Songs per User: </label>
          <input type="text" id="maxSpU" class="form-control" value=<?php echo $firebase->get($path . '/limit') ?>>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
      </form>

    </div>
  </div>
  <!-- Left side End -->

  <!-- Right side -->
  <div class="col-md-8" style="height: 100%;">
    <!-- Current Song Info -->
    <div class="col-md-12">
      <!-- maybe restaurant picture? -->
      <div class="jumbotron" style="padding: 20px;">
        <h2>Currently Playing:</h2>
        <p>Numb - Linkin Park</p>
        <!--
        Output of Song name and artist,
        maybe song length and current status (how many seconds has this song played)
        from Firebase
        -->
      </div>
    </div>
    <!-- Playlist -->
    <div class="col-md-12">
      <h4>Playlist</h4>
      <div id="queue" class="col-md-12" style="overflow-y: scroll; min-height: 50%">
        <!-- Get Playlist from Firebase -->

      </div>
    </div>
    <!-- Playlist functions -->
    <div class="col-md-12">
      <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-play"></span></button>
      <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-pause"></span></button>
      <button type="submit" class="btn btn-danger"><span>Clear Playlist</button>
      <button type="submit" class="btn btn-danger"><span>Delete Selected</button>
    </div>
  </div>
  <!-- Right side End -->
</div>
</body>
</html>
