<?php
// Firebase Path ---------------------------------------------------------------

const DEFAULT_PATH = '/';

$path = '';

// get all restaurants under current email adress
$statement = $pdo->prepare("SELECT roomName FROM users WHERE id = $userid");
$statement->execute();
$result = $statement->fetch();
$path = DEFAULT_PATH . $result[0] . "/";

$statement = $pdo->prepare("SELECT * FROM users WHERE id = $userid");
$statement->execute();
$result = $statement->fetch();

$email = $result['email'];

$statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$statement->execute(array(":email" => $email));
$result = $statement->fetchAll();

$rooms = json_encode($result);

?>
