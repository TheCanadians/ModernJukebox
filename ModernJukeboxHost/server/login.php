<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=modernjukeboxhost', 'root', '');

if(isset($_GET['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
  $result = $statement->execute(array('email' => $email));
  $user = $statement->fetch();

  if($user !== false && password_verify($password, $user['password'])) {
    $_SESSION['userid'] = $user['id'];
    header('Location: spotifyLogin.php');
    //die('Login erfolgreich. Weiter zu <a href="spotifyLogin.php">Home</a>');
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
 </head>
 <body>

   <?php
   if(isset($errorMessage)) {
     echo $errorMessage;
   }
    ?>

   <form action="?login=1" method="post">
     E-Mail:<br>
     <input type="email" size="40" maxlength="250" name="email"><br><br>

     Dein Passwort:<br>
     <input type="password" size="40" maxlength="250" name="password"><br>

     <input type="submit" value="Abschicken">
   </form>
   <p>Keinen Account? <a href="register.php">Neuer Account</a></p>
 </body>
 </html>
