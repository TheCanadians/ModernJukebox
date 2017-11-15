<?php
// is User logged in?
session_start();
if(!isset($_SESSION['userid'])) {
  die('Bitte zuerst <a href="login.php">einloggen</a>');
}

// Start Database
$pdo = new PDO('mysql:host=localhost;dbname=modernjukeboxhost', 'root', '');
// Get User ID from Session
$userid = $_SESSION['userid'];

// Get Composer Packages -------------------------------------------------------

require '../vendor/autoload.php';
require_once '../script/PHP/spotify.php';
require_once '../script/PHP/firebase.php';

// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------
 ?>

<html !DOCTYPE>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modern Jukebox Host - Home</title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <!-- JQuery -->
  <script src="../script/JS/jquery-3.2.1.js"></script>
  <!-- Firebase -->
  <script src="https://www.gstatic.com/firebasejs/4.6.0/firebase.js"></script>
  <!-- JsPDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.debug.js"></script>
  <!-- QRCodeJs -->
  <script type="text/javascript" src="../qrcodejs-master/jquery.min.js"></script>
  <script type="text/javascript" src="../qrcodejs-master/qrcode.js"></script>
  <!-- Load Scripts -->
  <script type="text/javascript" src="../script/JS/firebase.js"></script>
  <script type="text/javascript" src="../script/JS/qrCode.js"></script>
  <script type="text/javascript" src="../script/JS/spotify.js"></script>

  <script>
    var path = <?php echo $path; ?>;
  </script>

</head>
<body>
<div class="container col-md-12" style="padding: 0;">
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="homeJS.php">ModernJukebox Host</a>
      </div>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Your Rooms <span class="caret"></span></a>
          <ul class="dropdown-menu" id="rooms">
          </ul>
        </li>
        <li><a href="newRestaurant.php"><span class="glyphicon glyphicon-plus"></span> Add New Room</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#" onclick="qrCode()"><span class="glyphicon glyphicon-file"></span> QR-Code</a></li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
    <script>
    var rooms = <?php echo $rooms; ?>;
    for (i = 0; i < rooms.length; i++) {
      getRoomName(rooms[i]['roomName']);
    }
    function getRoomName(path) {
      database.ref('/' + path + '/name').once('value').then(function(snapshot) {
        var roomName = (snapshot.val());
        var listEl = document.createElement("LI")
        var linkEl = document.createElement("A");
        linkEl.setAttribute("href", "#");
        linkEl.setAttribute("id", path);
        //linkEl.setAttribute("onclick", "setRoom()");
        var roomText = document.createTextNode(roomName);
        linkEl.appendChild(roomText);
        listEl.appendChild(linkEl);
        var list = document.getElementById("rooms");
        list.insertBefore(listEl, list.childNodes[0]);
      });
    }
    function setRoom(event) {

    }
    $('.dropdown-menu').on('click', "a", function() {
      console.log("hello");
      $(this).parent().addClass('active');
    });
    </script>
  </nav>
  <!-- QRCode -->
  <div id="qrCodeHolder"></div>
  <div id="editor"></div>
  <!-- Left side -->
  <div class="col-md-4" style="height: 100%;">
    <!-- Restaurant Info -->
    <div class="col-md-12">
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
      <button type="submit" id="pause" class="btn btn-default"><span class="glyphicon glyphicon-pause"></span></button>
      <button type="submit" id="clearPlaylist" class="btn btn-danger"><span>Clear Playlist</button>
      <button type="submit" id="deleteSel" class="btn btn-danger"><span>Delete Selected</button>
      <button type="submit" id="player" class="btn btn-danger" onclick="openPlayer()"><span>Open Player</button>
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
        element.className = "col-md-12";
        element.id = song.id;
        element.style.border = "thin solid #000";
        element.style.padding = "2px";

        var p = document.createElement('p');
        p.style.margin = "0px";
        element.appendChild(p);

        var content = document.createTextNode(song.title + " - " + song.artist + " Votes: " + song.votes);
        p.appendChild(content);

        var checkbox = document.createElement('input');
        checkbox.type = "checkbox";
        checkbox.className = "checkbox";
        checkbox.style.cssFloat = "right";
        p.appendChild(checkbox);

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
        data: {PlayID : songID}
      }).done(function(msg) {
        console.log(msg);
      });
    });

    $('#pause').click(function() {
      var songID = document.getElementById("queue").firstChild.id;
      console.log(songID);
      $.ajax({
        type: "POST",
        url: "functions.php",
        data: {PauseID : songID}
      }).done(function(msg) {
        console.log(msg);
      });
    });

    $('#deleteSel').click(function() {
        var updates = {};
        var checkboxes = document.getElementsByClassName("checkbox");
        for (i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].checked == true) {
            var id = checkboxes[i].parentElement.parentElement;
            var checkID = id.getAttribute('id');
            updates[path + 'songs/' + checkID] = null;
          }
        }
        firebase.database().ref().update(updates);
    });

    $('#clearPlaylist').click(function() {
        database.ref(path + 'songs/').remove();
    });

</script>
