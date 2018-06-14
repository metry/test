<?php
function checkUser($pdo, string $email, string $name, string $phone)
{
    //проверяю есть ли такой пользователь
    $stm = $pdo->prepare('SELECT id FROM users WHERE email=:email');
    $stm->bindParam(':email', $email, PDO::PARAM_STR);
    $stm->execute();
    $result = $stm->fetchColumn();
    if (!$result) {
        //добавляю
        $stm = $pdo->prepare("INSERT INTO users (email, name, phone) values (:email, :name, :phone)");
        $stm->bindParam(':email', $email, PDO::PARAM_STR);
        $stm->bindParam(':name', $name, PDO::PARAM_STR);
        $stm->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stm->execute();
        return $pdo->lastInsertId();
    }
    //запись уже есть
    return $result;
}

function makeOrder(
    $pdo,
    int $userId,
    string $street,
    string $home,
    string $part,
    string $appt,
    string $floor,
    string $comment,
    int $payment,
    int $callback
) {

    $stm = $pdo->prepare('SELECT COUNT(id) FROM orders WHERE userId=:userId');
    $stm->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stm->execute();
    $count = $stm->fetchColumn();

    $stm = $pdo->prepare("INSERT INTO orders (userId, street, home, part, appt, floor, comment, payment, callback) 
      values (:userId, :street, :home, :part, :appt, :floor, :comment, :payment, :callback)");
    $stm->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stm->bindParam(':street', $street, PDO::PARAM_STR);
    $stm->bindParam(':home', $home, PDO::PARAM_STR);
    $stm->bindParam(':part', $part, PDO::PARAM_STR);
    $stm->bindParam(':appt', $appt, PDO::PARAM_STR);
    $stm->bindParam(':floor', $floor, PDO::PARAM_STR);
    $stm->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stm->bindParam(':payment', $payment, PDO::PARAM_INT);
    $stm->bindParam(':callback', $callback, PDO::PARAM_INT);
    $stm->execute();

    $order = 'Заказ №' . $pdo->lastInsertId() . PHP_EOL;
    $order .= 'Ваш заказ будет доставлен по адресу:' . PHP_EOL;
    $order .= 'Улица: ' . htmlspecialchars($street) . PHP_EOL;
    $order .= 'Дом: ' . htmlspecialchars($home) . PHP_EOL;
    $order .= 'Корпус: ' . htmlspecialchars($part) . PHP_EOL;
    $order .= 'Квартира: ' . htmlspecialchars($appt) . PHP_EOL;
    $order .= 'Этаж: ' . htmlspecialchars($floor) . PHP_EOL;
    $order .= 'Комментарий: ' . htmlspecialchars($comment) . PHP_EOL;
    //следующий участок обсудить
    if ($payment == 1) {
        $order .= 'Потребуется сдача' . PHP_EOL;
    }
    if ($payment == 2) {
        $order .= 'Оплата по карте' . PHP_EOL;
    }
    if ($callback == 1) {
        $order .= 'Не перезванивать' . PHP_EOL;
    }
    $order .= 'Содержимое заказа: DarkBeefBurger за 500 рублей, 1 шт' . PHP_EOL;

    if ($count == 0) {
        $msg = 'Спасибо - это ваш первый заказ';
    }
    $msg = 'Спасибо! Это ваш ' . ++$count . ' заказ';
    $order .= $msg;

    $mailDir = 'mail';
    if (!file_exists('../' . $mailDir)) {
        mkdir('../' . $mailDir);
    }
    file_put_contents('../' . $mailDir . '/msg'. date('Y_m_d_H_i_i'), $order);
    return $msg;
}
