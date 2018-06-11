<?php
//обсудить этот файл про вынос логики из шаблона
require('config.php');
$dsn = "mysql:host=" . HOST . ";dbname=" . DBNAME;
$pdo = new PDO($dsn, USER, PASS);

$sql = 'SELECT 
    O.id, O.street, O.home, O.part, O.appt, O.floor,
    O.comment, O.payment, O.callback, O.userId, U.email, 
    U.name, U.phone 
    FROM orders AS O 
    LEFT JOIN users AS U ON O.userId=U.id 
    ORDER BY O.id DESC';
$stmt = $pdo->query($sql);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo 'Заказы<br>';
echo '<table border="1">';
echo '<tr>';
echo '<td>Номер заказа</td><td>Улица</td><td>Дом</td><td>Корпус</td><td>Квартира</td><td>Этаж</td>';
echo '<td>Комментарий</td><td>Информация о оплате</td><td>Не перезванивать</td>';
echo '<td>Идентификатор пользователя</td><td>email</td><td>Имя</td><td>Телефон</td>';
echo '</tr>';
foreach ($result as $item) {
    echo '<tr>';
    echo '<td>' . $item['id'] . '</td>';
    echo '<td>' . htmlspecialchars($item['street']) . '</td>';
    echo '<td>' . htmlspecialchars($item['home']) . '</td>';
    echo '<td>' . htmlspecialchars($item['part']) . '</td>';
    echo '<td>' . htmlspecialchars($item['appt']) . '</td>';
    echo '<td>' . htmlspecialchars($item['floor']) . '</td>';
    echo '<td>' . htmlspecialchars($item['comment']) . '</td>';
    if ($item['payment'] == 1) {
        echo '<td>Потребется сдача</td>';
    } elseif ($item['payment'] == 2) {
        echo '<td>Оплата по карте</td>';
    } else {
        echo '<td>-</td>';
    }
    if ($item['callback'] == 1) {
        echo '<td>Да</td>';
    } else {
        echo '<td>-</td>';
    }
    echo '<td>' . $item['userId'] . '</td>';
    echo '<td>' . htmlspecialchars($item['email']) . '</td>';
    echo '<td>' . htmlspecialchars($item['name']) . '</td>';
    echo '<td>' . htmlspecialchars($item['phone']) . '</td>';
    echo '</tr>';
}
echo '</table>';

$sql = 'SELECT id, email, name, phone FROM users ORDER BY id DESC';
$stmt = $pdo->query($sql);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo 'Пользователи<br>';
echo '<table border="1">';
echo '<tr>';
echo '<td>Идентификатор пользователя</td><td>email</td><td>Имя</td><td>Телефон</td>';
echo '</tr>';
foreach ($result as $item) {
    echo '<td>' . $item['id'] . '</td>';
    echo '<td>' . htmlspecialchars($item['email']) . '</td>';
    echo '<td>' . htmlspecialchars($item['name']) . '</td>';
    echo '<td>' . htmlspecialchars($item['phone']) . '</td>';
    echo '</tr>';
}
echo '</table>';
