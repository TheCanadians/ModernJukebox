<?php
session_start();

if(!isset($_SESSION['userid'])) {
  die('Bitte zuerst <a href="login.php">einloggen</a>');
}

$pdo = new PDO('mysql:host=db6.variomedia.de;dbname=db26677', 'u26677', 'm89UDTTU');
$userid = $_SESSION['userid'];

$statement = $pdo->prepare("SELECT * FROM users WHERE id = :userid");
$result = $statement->execute(array('userid' => $userid));
$user = $statement->fetch();
?>
<?php
// Save values in new database entry
if(isset($_GET['newRes'])) {
  $error = false;
  $name = $_POST['name'];
  // Change name again
  $nodeName = strtolower($name);
  $nodeName = str_replace(" ", "-", $nodeName);
  $nodeName = preg_replace('/[^A-Za-z\-]/', '', $nodeName);

  $email = $user['email'];
  $pw = $user['pw'];
  $roomName = $nodeName;
  $accessToken = $user['accessToken'];
  $refreshToken = $user['refreshToken'];

  $statement = $pdo->prepare("SELECT * FROM users WHERE roomName = :roomName");
  $result = $statement->execute(array('roomName' => $roomName));
  $roomExist = $statement->fetchAll();



  if (!empty($roomExist)) {
    $errorMes = "This room exists already, please name it differently!<br>";
  }
  else {
    $statement2 = $pdo->prepare("INSERT INTO users (email, pw, roomName, accessToken, refreshToken) VALUES (:email, :password, :roomName, :accessToken, :refreshToken)");
    $result = $statement2->execute(array(':email' => $email, ':password' => $pw, ':roomName' => $roomName, ':accessToken' => $accessToken, ':refreshToken' => $refreshToken));

    header('Location: homeJS.php');
    die();
  }


}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Create A New Restaurant</title>
    <!-- Firebase -->
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
    </script>
  </head>
  <body>
    <?php
    if(isset($errorMes)) {
      echo $errorMes;
    }
     ?>
    <form id="newRes" action="?newRes=1" method="post">
      <div class="form-group">
        <label for="name">Restaurant Name: </label>
        <input type="text" name="name" class="form-control">
      </div>
      <div class="form-group">
        <label for="image">Image Link: </label>
        <input type="text" name="image" class="form-control">
      </div>
      <div class="form-group">
        <label for="maxQ">Max Queue: </label>
        <input type="text" name="maxQ" class="form-control" value="0">
      </div>
      <div class="form-group">
        <label for="maxSpU">Max Votes per User: </label>
        <input type="text" name="maxSpU" class="form-control" value="0">
      </div>
      <button type="submit" class="btn btn-primary" value="Submit" onclick="Create()">Create Restaurant</button>
    </form>
  </body>
</html>
<script>
// Save values in new firebase entry
  function Create() {
    var form = document.getElementById("newRes");
    var name = form.elements["name"].value;
    var imageLink = form.elements["image"].value;
    var maxQ = form.elements["maxQ"].value;
    var maxSpU = form.elements["maxSpU"].value;
    // Cut spaces and write small
    var nodeName = name.toLowerCase();
    nodeName =nodeName.replace(/[^\w\s]/gi, '');
    nodeName = nodeName.replace(/ /g, '-');
    nodeName = nodeName.replace(/_/g, '');
    //check if node exists
    database.ref().once('value', function(snapshot) {
      if (snapshot.hasChild(nodeName)) {
        console.log("exists");
      }
      else {
        // Push data to firebase
        database.ref(nodeName + '/maxQueue').set(maxQ);
        database.ref(nodeName + '/limit').set(maxSpU);
        database.ref(nodeName + '/name').set(name);
        database.ref(nodeName + '/image').set(imageLink);
        database.ref(nodeName + '/id').set(nodeName);
      }
    });

  }
</script>
