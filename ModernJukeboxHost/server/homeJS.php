<?php
// is User logged in?
session_start();
if(!isset($_SESSION['userid'])) {
  die('Bitte zuerst <a href="login.php">einloggen</a>');
}

// Start Database
$pdo = new PDO('mysql:host=db6.variomedia.de;dbname=db26677', 'u26677', 'm89UDTTU');
// Get User ID from Session
$userid = $_SESSION['userid'];

// Get Composer Packages -------------------------------------------------------

require '../vendor/autoload.php';
//require_once '../script/PHP/spotify.php';
require_once '../script/PHP/firebase.php';
require_once '../script/PHP/spotify.php';

// -----------------------------------------------------------------------------
// -----------------------------------------------------------------------------


 ?>

<html !DOCTYPE>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modern Jukebox Host - Home</title>
  <!-- Declare Variables -->
  <script>
    path = <?php echo $path; ?>;
    rooms = <?php echo $rooms; ?>;
    accessToken = <?php echo $accessToken; ?>;
    refreshToken = <?php echo $refreshToken; ?>;
    cliID = <?php echo $cliID; ?>;;
    cliSecret = <?php echo $cliSecret; ?>;;
  </script>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css" >
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <!-- JQuery -->
  <script src="../script/JS/jquery-3.2.1.js"></script>
  <!-- Firebase -->
  <script src="https://www.gstatic.com/firebasejs/4.6.0/firebase.js"></script>
  <!-- QRCodeJs -->
  <script type="text/javascript" src="../qrcodejs-master/jquery.min.js"></script>
  <script type="text/javascript" src="../qrcodejs-master/qrcode.js"></script>
  <!-- Load Scripts -->
  <script type="text/javascript" src="../script/JS/bundle.js"></script>
  <script type="text/javascript" src="../script/JS/firebase.js"></script>
  <script type="text/javascript" src="../script/JS/qrCode.js"></script>
  <script type="text/javascript" src="../script/JS/player.js"></script>
  <script type="text/javascript" src="../script/JS/jukebox.js"></script>
  <script type="text/javascript" src="../script/JS/playlist.js"></script>
  <script type="text/javascript" src="../script/JS/resInfo.js"></script>
  <script type="text/javascript" src="../script/JS/button.js"></script>
  <!-- JsPDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.debug.js"></script>
</head>
<!-- Set right firebase Path on load -->
<body onload="setInfoPath();">
<div class="container col-md-12" style="padding: 0;">
  <!-- Navigation bar -->
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <ul class="nav navbar-nav">
        <!-- Restaurant dropdown -->
        <li class="dropdown">
          <a class="dropdown-toggle" id="dropdownButton" data-toggle="dropdown" href="#">Your Rooms <span class="caret"></span></a>
          <ul class="dropdown-menu" id="rooms">
          </ul>
        </li>
        <li><a href="newRestaurant.php"><span class="glyphicon glyphicon-plus"></span> Add New Room</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <!-- saves QR-Code of path variable as PDF -->
        <li><a href="#" onclick="qrCode()"><span class="glyphicon glyphicon-file"></span> QR-Code</a></li>
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </nav>
  <!-- QRCode -->
  <div id="qrCodeHolder"></div>
  <!-- Left side -->
  <div class="col-md-4" style="border-right: thin solid #000;">
    <!-- Restaurant Info -->
    <div class="col-md-12">
      <!-- Restaurant Info Formular -->
      <form id="ResInfo" method="post">
        <div class="form-group">
          <label for="imgLink">Image Link: </label>
          <input type="text" id="imgLink" name="imgLink" class="form-control" value="0">
        </div>
        <div class="form-group">
          <label for="maxQ">Max Queue: </label>
          <input type="text" id="maxQ" name="maxQ" class="form-control" value="0">
        </div>
        <div class="form-group">
          <label for="maxSpU">Max Songs per User: </label>
          <input type="text" id="maxSpU" name="maxSpU" class="form-control" value="0">
        </div>
      </form>
      <!-- Blacklist -->
      <div class="col-md-12">
        <h4 style="display: inline-block;">Blacklist</h4>
        <a href="#" style="margin-top: 13px" onclick="toggleSearch()"><span class="glyphicon glyphicon-search"></span></a>
        <input type="checkbox" name="checkAll" id="checkAll" style="float: right; margin-top: 13px">
      </div>
      <div class="col-md-12 form-group" id="searchDIV" style="display: none">
        <input type="text" id="search" class="form-control" onkeyup="searchGenres()" placeholder="Search for genres...">
      </div>
      <div id="blacklist" class="col-md-12 list-group" style="overflow-y: scroll; max-height: 32%; padding-right: 0">
      </div>
      <button class="btn btn-primary" onclick="update()">Update</button>
    </div>
  </div>
  <!-- Left side End -->

  <!-- Right side -->
  <div class="col-md-8">
    <!-- Current Song Info -->
    <div class="col-md-12">
      <div class="col-md-12 list-group" id="currentSong">
        <div class="col-md-12 list-group-item" style="border: none">
          <p id="playing" style="margin: 0px">Playing: -</p>
        </div>
        <div class="col-md-12 list-group-item" style="border: none">
          <p id="next" style="margin: 0px">Next Song: -</p>
        </div>
      </div>
    </div>
    <!-- Playlist -->
    <div class="col-md-12">
      <h4>Playlist</h4>
      <div id="queue" class="col-md-12 list-group">
      </div>
    </div>
    <!-- Playlist functions -->
    <div class="col-md-12" style="
    display:  flex;
    flex-direction: row;
    justify-content: space-evenly;
    align-items:  center;
    flex-flow: wrap;
    margin-bottom: 4rem;">
      <button type="submit" id="play" class="btn btn-default"><span class="glyphicon glyphicon-play"></span></button>
      <button type="submit" id="pause" class="btn btn-default"><span class="glyphicon glyphicon-pause"></span></button>
      <button type="submit" id="clearPlaylist" class="btn btn-danger"><span>Clear Playlist</button>
      <button type="submit" id="deleteSel" class="btn btn-danger"><span>Delete Selected</button>
      <button type="submit" id="player" class="btn btn-danger"><span>Open Player</button>
    </div>
  </div>
  <!-- Right side End -->
</div>
</body>
</html>
