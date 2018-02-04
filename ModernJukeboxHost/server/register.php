<?php
session_start();
$pdo = new PDO('mysql:host=db6.variomedia.de;dbname=db26677', 'u26677', 'm89UDTTU');
?>

 <!DOCTYPE html>
 <html>
 <head>
   <title>Registrierung</title>

   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css" >
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
   <?php
   $showFormular = true;

   if(isset($_GET['register'])) {
     $error = false;
     $email = $_POST['email'];
     $password = $_POST['password'];
     $password2 = $_POST['password2'];

     if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       echo 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
       $error = true;
     }
     if(strlen($password) == 0) {
       echo 'Bitte ein Passwort angeben<br>';
       $error = true;
     }
     if($password != $password2) {
       echo 'Die Passwörter müssen übereinstimmen<br>';
       $error = true;
     }

     if(!$error) {
       $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
       $result = $statement->execute(array('email' => $email));
       $user = $statement->fetch();

       if($user !== false) {
         echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
         $error = true;
       }
     }

     if(!$error) {
       $passwort_hash = password_hash($password, PASSWORD_DEFAULT);

       $statement = $pdo->prepare("INSERT INTO users (email, pw, roomName) VALUES (:email, :password, 'Default')");
       $result = $statement->execute(array('email' => $email, 'password' => $passwort_hash));

       if($result) {
         echo 'Du wurdest erfolgreich registriert. <a href="login.php">Zum Login</a>';
         $showFormular = false;
       }
       else {
         echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
       }
     }
   }

   if($showFormular) {
     ?>

     <form action="?register=1" method="post" style="padding: 1rem;">
      <div class="form-group">
        <label for="name">Email: </label>
        <input class="form-control" type="email" size="40" maxlength="250" name="email">
      </div>

      <div class="form-group">
        <label for="name">Dein Passwort: </label>
        <input class="form-control" type="password" size="40" maxlength="250" name="password">
      </div>

      <div class="form-group">
        <label for="name">Passwort wiederholen: </label>
        <input class="form-control" type="password" size="40" maxlength="250" name="password2">
      </div>

       <input class="btn btn-primary" id="registerBTN" type="submit" value="Abschicken">
     </form>
     <p style="padding: 0 1rem;">Schon einen Account? <a href="login.php">Login</a></p>
     <?php
   }
    ?>
  </body>
  </html>
