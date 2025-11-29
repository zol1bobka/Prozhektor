<?php
require_once __DIR__.'/config.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){ header('Location: index.html#footer'); exit; }

$name=$_POST['name']??''; $email=$_POST['email']??''; $msg=$_POST['message']??'';
if($name==''||$email==''||$msg==''){ echo 'Ошибка: заполните поля'; exit; }

$pdo=get_pdo();
$q=$pdo->prepare("INSERT INTO contacts(name,email,message,created_at)
VALUES(:n,:e,:m,NOW())");
$q->execute([':n'=>$name,':e'=>$email,':m'=>$msg]);

header('Location: index.html#footer');
?>