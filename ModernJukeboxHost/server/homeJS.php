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
}


$api->play(false);

// Firebase Path ---------------------------------------------------------------

const DEFAULT_PATH = '/';

$path = '';

$statement = $pdo->prepare("SELECT roomName FROM users WHERE id = $userid");
$statement->execute();
$result = $statement->fetch();
$path = DEFAULT_PATH . $result[0] . "/";

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
  <script src="../script/jquery-3.2.1.js"></script>
  <script src="https://www.gstatic.com/firebasejs/4.6.0/firebase.js"></script>
  <script>
    // Initialize Firebase
    var config = {
      apiKey: "AIzaSyCW-yHDVzJT7IgK6exI1AElYZ85BKiKeBc",
      authDomain: "modern-jukebox.firebaseapp.com",
      databaseURL: "https://modern-jukebox.firebaseio.com",
      storageBucket: "",
      messagingSenderId: "560034994179"
    };
    firebase.initializeApp(config);

    var database = firebase.database();
    var path = <?php echo $path; ?>;
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
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </nav>
  <!-- Left side -->
  <div class="col-md-4" style="height: 100%;">
    <!-- Restaurant Info -->
    <div class="col-md-12">
      <select class="form-control" id="ResSelect">
        <!-- Get restaurants from Database -->
        <script>
        database.ref(path + 'name').once('value').then(function(snapshot) {
          var roomName = (snapshot.val());
          var option = document.createElement("OPTION");
          var optionText = document.createTextNode(roomName);
          option.appendChild(optionText);
          document.getElementById("ResSelect").appendChild(option);
        });
        </script>
      </select>
      <!-- Restaurant Info Formular -->
      <form id="ResInfo" method="post">
        <div class="form-group">
          <label for="maxQ">Max Queue: </label>
          <input type="text" id="maxQ" name="maxQ" class="form-control" value="0">
        </div>
        <div class="form-group">
          <label for="maxSpU">Max Songs per User: </label>
          <input type="text" id="maxSpU" name="maxSpU" class="form-control" value="0">
        </div>
        <button type="submit" class="btn btn-primary" value="submit" onclick="update()">Update</button>
      </form>
      <script>
      var queue = database.ref(path + 'maxQueue');
      var limit = database.ref(path + 'limit');
      queue.on('value', function(snapshot) {
        document.getElementById("maxQ").value = snapshot.val();
      });
      limit.on('value', function(snapshot) {
        document.getElementById("maxSpU").value = snapshot.val();
      });
      function update() {
        var form = document.getElementById("ResInfo");
        var maxQ = form.elements["maxQ"].value;
        var maxSpU = form.elements["maxSpU"].value;

        database.ref(path + 'maxQueue').set(maxQ);
        database.ref(path + 'limit').set(maxSpU);
      }
      </script>
    </div>
  </div>
  <!-- Left side End -->

  <!-- Right side -->
  <div class="col-md-8" style="height: 100%;">
    <!-- Current Song Info -->
    <div class="col-md-12">
      <iframe src="https://open.spotify.com/embed?uri=spotify%3Auser%3Aspotify%3Aplaylist%3A2PXdUld4Ueio2pHcB6sM8j&theme=white" width="300" height="80" frameborder="0" allowtransparency="true"></iframe>
    </div>
    <!-- Playlist -->
    <div class="col-md-12">
      <h4>Playlist</h4>
      <div id="queue" class="col-md-12" style="overflow-y: scroll; min-height: 50%">
      </div>
    </div>
    <!-- Playlist functions -->
    <div class="col-md-12">
      <button type="submit" id="play" class="btn btn-default"><span class="glyphicon glyphicon-play"></span></button>
      <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-pause"></span></button>
      <button type="submit" class="btn btn-danger"><span>Clear Playlist</button>
      <button type="submit" class="btn btn-danger"><span>Delete Selected</button>
    </div>
  </div>
  <!-- Right side End -->
</div>
</body>
</html>
<script>
//Get Playlist from Firebase
  database.ref(path + 'songs/').orderByChild('votes').on('value', function(snapshot) {
    var playlist = document.getElementById("queue");
    while(playlist.firstChild) {
      playlist.removeChild(playlist.firstChild);
    }
    snapshot.forEach(function(child) {
      var song = child.val();
      console.log(song.artist+': '+song.votes);
      if(document.getElementById(song.id) != null) {

      }
      else {
        var element = document.createElement('div');
        element.class = "col-md-12";
        element.id = song.id;
        var content = document.createTextNode(song.title + " - " + song.artist + " Votes: " + song.votes);
        element.appendChild(content);
        var queue = document.getElementById("queue");
        queue.appendChild(element);
      }
    });
  });


    $('#play').click(function() {
      var songID = document.getElementById("queue").firstChild.id;
      console.log(songID);
      $.ajax({
        type: "POST",
        url: "functions.php",
        data: {id : songID}
      }).done(function(msg) {
        alert("Data saved: " + msg);
      });
    });

</script>
