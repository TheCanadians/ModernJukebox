<?php
// is User logged in?
session_start();
if(!isset($_SESSION['userid'])) {
  die('Bitte zuerst <a href="login.php">einloggen</a>');
}

$pdo = new PDO('mysql:host=localhost;dbname=modernjukeboxhost', 'root', 'root');
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

// Firebase PHP Stuff ----------------------------------------------------------

const DEFAULT_URL = 'https://modern-jukebox.firebaseio.com/';
const DEFAULT_TOKEN = 'oi5JKlnTahYEyORi9YvlvY36pn9nxByDJjx0FX0j';
const DEFAULT_PATH = '/';

$path = '';

$statement = $pdo->prepare("SELECT roomName FROM users WHERE id = $userid");
$statement->execute();
$result = $statement->fetch();
$path = DEFAULT_PATH . $result[0] . "/";

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
  <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="../script/jquery-3.2.1.js"></script>
  <script>
    var source = new EventSource('<?php echo DEFAULT_URL . $path; ?>/songs.json');
    console.log(<?php echo $path ?>);
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

    source.addEventListener("put", function(e) {
      console.log("Put UP - " + e.data);
      var dataVar = JSON.parse(e.data);

      var songs = dataVar.data;

      if(dataVar.path != "/" && dataVar.data.artist != null) {
        var element = document.createElement('div');
        element.class = "col-12";

        var content = document.createTextNode(dataVar.data.title + " - " + dataVar.data.artist);
        element.appendChild(content);

        var queue = document.getElementById("queue");
        queue.appendChild(element);
      }
      else {
        for(var key in songs) {
          var element = document.createElement('div');
          element.class = "col-12";

          var content = document.createTextNode(songs[key].title + " - " + songs[key].artist);
          element.appendChild(content);

          var queue = document.getElementById("queue");
          queue.appendChild(element);
        }
      }
    }, false);
  </script>
</head>
<body>
<div class="container col-12" style="padding: 0;">
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
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </nav>
  <!-- Left side -->
  <div class="col-12" id="left-side" >
    <!-- Restaurant Info -->
    <div class="col-12">
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
      <form id="restForm" action="javascript:void(0);" method="post">
        <div class="form-group">
          <label for="maxQ">Max Queue: </label>
          <input type="text" name="maxQ" class="form-control" value=<?php echo $firebase->get($path . '/maxQueue'); ?>>
        </div>
        <div class="form-group">
          <label for="maxSpU">Max Songs per User: </label>
          <input type="text" name="maxSpU" class="form-control" value=<?php echo $firebase->get($path . '/limit') ?>>
        </div>
        <button type="submit" class="btn btn-primary" value="submit">Update</button>
      </form>
      <script>
        $("form").submit(function() {
          var str = $(this).serialize();
          $.ajax('update.php', str, function(result) {
            console.log(result);
          });
          return false;
        });
      </script>
    </div>
  </div>
  <!-- Left side End -->

  <!-- Right side -->
  <div class="col-12" id="right-side">
    <!-- Current Song Info -->
    <div class="col-12" id="currenSong">
      <!-- maybe restaurant picture? -->
      <div class="jumbotron" style="padding: 24px;">
        <h2 id="nowPlaying">Now Playing:</h2>
        <p id="nowPlayingInfo">Numb - Linkin Park</p>
        <!--
        Output of Song name and artist,
        maybe song length and current status (how many seconds has this song played)
        from Firebase
        -->
      </div>
    </div>
    <!-- Playlist -->
    <div class="col-12" id="playlist">
      <h4>Playlist</h4>
      <div id="queue" class="col-12">
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
    <div class="col-12" id="functions">
      <div id="controls">
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-play"></span></button>
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-pause"></span></button>
      </div>
      <button type="submit" class="btn btn-danger"><span>Clear Playlist</button>
      <button type="submit" class="btn btn-danger"><span>Delete Selected</button>
    </div>
  </div>
  <!-- Right side End -->
</div>
</body>
</html>
