<?php
// is User logged in?
session_start();
if(!isset($_SESSION['userid'])) {
  die('Bitte zuerst <a href="login.php">einloggen</a>');
}

$pdo = new PDO('mysql:host=localhost;dbname=modernjukeboxhost', 'root', '');
$userid = $_SESSION['userid'];

// Spotify Stuff ---------------------------------------------------------------

require '../vendor/autoload.php';

// Get access token from database
$statement = $pdo->prepare("SELECT accessToken FROM users WHERE id = $userid");
$statement->execute();
$result = $statement->fetch();
$accessToken = $result;


$api = new SpotifyWebAPI\SpotifyWebAPI();
$api->setAccessToken($accessToken);

// Firebase Stuff --------------------------------------------------------------

const DEFAULT_URL = 'https://modern-jukebox.firebaseio.com';
const DEFAULT_TOKEN = 'oi5JKlnTahYEyORi9YvlvY36pn9nxByDJjx0FX0j';
const DEFAULT_PATH = '/';

$path = '';

$firebase = new \Firebase\FirebaseLib(DEFAULT_URL, DEFAULT_TOKEN);

// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
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
  <script>
    var source = new EventSource('https://modern-jukebox.firebaseio.com/<?php echo $path; ?>.json');
    source.onmessage = function(e){
      document.getElementById("queue").innerHTML += e.data + '<br>';
      console.log('message');
    };

    source.addEventListener("message", function(e) {
      console.log(e.data);
    }, false);

    source.addEventListener("open", function(e) {
      console.log("Connection was opened");
    }, false);

    source.addEventListener("error", function(e) {
      console.log("Error - connection was lost.");
    }, false);

    source.addEventListener("patch", function(e) {
      console.log("Patch UP - " + e.data);
    }, false);

    source.addChildEventListener("put", function(e) {
      console.log("Put UP - " + e.data);
      var dataVar = JSON.parse(e.data);
      console.log(dataVar);
      console.log(dataVar["path"]);
      //document.getElementById("queue").innerHTML += dataVar + '<br>';
    }, false);
  </script>
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
        <?php
        /*
          $songs = json_decode($firebase->get($path . '/songs'), true);
          foreach($songs as $song) {
            echo '<div class="col-md-12">';
              echo $song['title'] . ' - ' . $song['artist'];
            echo '</div>';
          }
          */
        ?>

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
