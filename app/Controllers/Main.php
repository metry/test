<?php

namespace App\Controllers;

use App\Models\Main as MMain;

class Main extends MainController
{
    public function index()
    {
        $this->view->renderTemplate('template');
    }

    public function admin()
    {
        $model = new MMain();
        $orders = $model->getOrders();
        $users = $model->getUsers();

        $this->view->twigLoad('admin', ['orders'=>$orders, 'users'=>$users]);
    }

    public function send()
    {
         //Валидация данных

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
            echo json_encode(['errors'=>$errors]);
            return null;
        }

         //Вставка значений в БД

        $model = new MMain();

        $userId = $model->checkUser(mb_strtolower($_POST['email']), $_POST['name'], $_POST['phone']);
        $makeOrder = $model->makeOrder(
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

         //Формирование текста письма

        $order = htmlspecialchars($_POST['name']) . ', вы сделали заказ!' . PHP_EOL;
        $order .= 'Номер заказа ' . $makeOrder['orderNumber'] . PHP_EOL;
        $order .= 'Ваш заказ будет доставлен по адресу:' . PHP_EOL;
        $order .= 'Улица: ' . htmlspecialchars($_POST['street']) . PHP_EOL;
        $order .= 'Дом: ' . htmlspecialchars($_POST['home']) . PHP_EOL;
        $order .= 'Корпус: ' . htmlspecialchars($_POST['part']) . PHP_EOL;
        $order .= 'Квартира: ' . htmlspecialchars($_POST['appt']) . PHP_EOL;
        $order .= 'Этаж: ' . htmlspecialchars($_POST['floor']) . PHP_EOL;
        $order .= 'Комментарий: ' . htmlspecialchars($_POST['comment']) . PHP_EOL;

        $payment = (int)$_POST['payment'];
        $callback = (int)$_POST['callback'];

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

        $msg = ($makeOrder['count'] == 0) ? 'Спасибо! Это ваш первый заказ' :
            'Спасибо! Это ваш ' . ++$makeOrder['count'] . ' заказ';
        $order .= $msg;

         //Отправка письма

        $transport = (new \Swift_SmtpTransport(SMTP_HOST, SMTP_PORT, SMTP_SECURITY))
            ->setUsername(EMAIL)
            ->setPassword(USER_PASSWORD);
        $mailer = new \Swift_Mailer($transport);
        $message = (new \Swift_Message('Вы заказали доставку'))
            ->setFrom([EMAIL => USER_NAME])
            ->setTo([trim($_POST['email']) => trim($_POST['name'])])
            ->setBody($order);
        if ($mailer->send($message)) {
            echo json_encode(['msg'=>$msg]);
        }
    }
}
