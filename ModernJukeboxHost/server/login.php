<?php
session_start();
$pdo = new PDO('mysql:host=db6.variomedia.de;dbname=db26677', 'u26677', 'm89UDTTU');

if(isset($_GET['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
  $result = $statement->execute(array('email' => $email));
  $user = $statement->fetch();

  if($user !== false && password_verify($password, $user['pw'])) {
    $_SESSION['userid'] = $user['id'];
    // autimatically forward to spotify login
    header('Location: spotifyLogin.php');
    die();
  }
  else {
    $errorMessage = "E-Mail oder Password war ungültig<br>";
  }
}
 ?>

 <!DOCTYPE html>
 <html>
 <head>
   <title>Login</title>

   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css" >
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>

   <?php
   if(isset($errorMessage)) {
     echo $errorMessage;
   }
    ?>

   <form action="?login=1" method="post" style="padding: 1rem;">

    <div class="form-group">
      <label for="name">Email: </label>
      <input class="form-control" type="email" size="40" maxlength="250" name="email">
    </div>

    <div class="form-group">
      <label for="name">Dein Passwort: </label>
      <input class="form-control" type="password" size="40" maxlength="250" name="password">
    </div>

     <input class="btn btn-primary" id="loginBTN" type="submit" value="Abschicken">
   </form>
   <p style="padding: 0 1rem;">Keinen Account? <a href="register.php">Neuer Account</a></p>
 </body>
 </html>
