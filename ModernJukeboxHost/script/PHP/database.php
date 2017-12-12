<?php
// is User logged in?
session_start();
if(!isset($_SESSION['userid'])) {
  die('Bitte zuerst <a href="login.php">einloggen</a>');
}

$pdo = new PDO('mysql:host=localhost;dbname=modernjukeboxhost', 'root', '');
$userid = $_SESSION['userid'];

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// get playlist id
if ($_POST['get'] == "true") {
  $path = $_POST['path'];
  $path = substr($path, 1, -1);
  $statement = $pdo->prepare("SELECT playlistID FROM users WHERE id = :id AND roomName = :roomName");
  $statement->execute(array('id' => $userid, 'roomName' => $path));
  $result = $statement->fetch();
  $playlistID = $result;
  echo $playlistID[0];
}
// set playlist id
if ($_POST['set'] == "true") {
  $pID = $_POST['playlistID'];
  $path = $_POST['path'];
  $path = substr($path, 1, -1);
  $statement = $pdo->prepare("UPDATE users SET playlistID = :playlistID WHERE id = :id AND roomName = :path");
  $result = $statement->execute(array('playlistID' => $pID, 'id' => $userid, 'path' => $path));
}

?>
