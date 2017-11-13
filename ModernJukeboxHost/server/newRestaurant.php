<?php
session_start();

if(!isset($_SESSION['userid'])) {
  die('Bitte zuerst <a href="login.php">einloggen</a>');
}

$pdo = new PDO('mysql:host=localhost;dbname=modernjukeboxhost', 'root', '');
$userid = $_SESSION['userid'];
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
    <form id="newRes" action="?newRes=1" method="post">
      <div class="form-group">
        <label for="name">Restaurant Name: </label>
        <input type="text" name="name" class="form-control">
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
  function Create() {
    var form = document.getElementById("newRes");
    var name = form.elements["name"].value;
    var maxQ = form.elements["maxQ"].value;
    var maxSpU = form.elements["maxSpU"].value;
    // Cut spaces and write small
    var nodeName = name.
    // Push data to firebase
    database.ref().push(nodeName);
    database.ref(nodeName + 'maxQueue').set(maxQ);
    database.ref(nodeName + 'limit').set(maxSpU);
    database.ref(nodeName + 'name').set(name);
  }
</script>
<?php
if(isset($_GET['newRes'])) {
  $error = false;
  $name = $_POST['name'];
  // Change name again
  $nodeName = ;
}
?>
