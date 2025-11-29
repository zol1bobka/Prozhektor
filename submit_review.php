<?php
require_once __DIR__.'/config.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){ header('Location: index.html#block3'); exit; }

$name=$_POST['name']??''; $text=$_POST['text']??''; 
$rating=(int)($_POST['rating']??0);

if($name==''||$text==''){ echo 'Ошибка: заполните поля'; exit; }
if($rating<1||$rating>5)$rating=null;

$pdo=get_pdo();
$q=$pdo->prepare("INSERT INTO reviews(name,rating,text,created_at) VALUES(:n,:r,:t,NOW())");
$q->execute([':n'=>$name,':r'=>$rating,':t'=>$text]);

header('Location: index.html#block3');
?>