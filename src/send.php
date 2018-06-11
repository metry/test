<?php
require('config.php');
require('functions.php');

$dsn = "mysql:host=" . HOST . ";dbname=" . DBNAME;
$pdo = new PDO($dsn, USER, PASS);

//простейшая валидация, тут можно обсудить
if (empty($_POST['email'])) {
    $errors[] = 'Заполните поле email';
}
if (empty($_POST['name'])) {
    $errors[] = 'Заполните поле имя';
}
if (empty($_POST['phone'])) {
    $errors[] = 'Заполните поле телефон';
}
if (empty($_POST['street'])) {
    $errors[] = 'Заполните поле Улица';
}

if ($errors) {
    $result['errors'] = $errors;
    echo json_encode($result);
    exit();
    //узнать что лучше использовать: exit() или лучше вторую ветку ветвления
}

$userId = checkUser($pdo, mb_strtolower($_POST['email']), $_POST['name'], $_POST['phone']);
$result['msg'] = makeOrder(
    $pdo,
    $userId,
    $_POST['street'],
    $_POST['home'],
    $_POST['part'],
    $_POST['appt'],
    $_POST['floor'],
    $_POST['comment'],
    (int)$_POST['payment'],
    (int)$_POST['callback']
);
echo json_encode($result);
