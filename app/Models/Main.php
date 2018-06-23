<?php

namespace App\Models;

class Main extends MainModel
{
    public function getOrders()
    {
        $sql = 'SELECT 
        O.id, O.street, O.home, O.part, O.appt, O.floor,
        O.comment, O.payment, O.callback, O.userId, U.email, 
        U.name, U.phone 
        FROM orders AS O 
        LEFT JOIN users AS U ON O.userId=U.id 
        ORDER BY O.id DESC';
        $stmt = $this->dbConnection->query($sql);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function getUsers()
    {
        $sql = 'SELECT id, email, name, phone FROM users ORDER BY id DESC';
        $stmt = $this->dbConnection->query($sql);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function checkUser(string $email, string $name, string $phone)
    {
        //проверяю есть ли такой пользователь
        $stm = $this->dbConnection->prepare('SELECT id FROM users WHERE email=:email');
        $stm->bindParam(':email', $email, \PDO::PARAM_STR);
        $stm->execute();
        $result = $stm->fetchColumn();
        if (!$result) {
            //добавляю
            $stm = $this->dbConnection->prepare("INSERT INTO users (
            email, name, phone) values (:email, :name, :phone)");
            $stm->bindParam(':email', $email, \PDO::PARAM_STR);
            $stm->bindParam(':name', $name, \PDO::PARAM_STR);
            $stm->bindParam(':phone', $phone, \PDO::PARAM_STR);
            $stm->execute();
            return $this->dbConnection->lastInsertId();
        }
        //запись уже есть
        return $result;
    }

    public function makeOrder(
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

        $stm = $this->dbConnection->prepare('SELECT COUNT(id) FROM orders WHERE userId=:userId');
        $stm->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stm->execute();
        $count = $stm->fetchColumn();

        $stm = $this->dbConnection->prepare("INSERT INTO orders (
        userId, street, home, part, appt, floor, comment, payment, callback) 
        values (:userId, :street, :home, :part, :appt, :floor, :comment, :payment, :callback)");
        $stm->bindParam(':userId', $userId, \PDO::PARAM_INT);
        $stm->bindParam(':street', $street, \PDO::PARAM_STR);
        $stm->bindParam(':home', $home, \PDO::PARAM_STR);
        $stm->bindParam(':part', $part, \PDO::PARAM_STR);
        $stm->bindParam(':appt', $appt, \PDO::PARAM_STR);
        $stm->bindParam(':floor', $floor, \PDO::PARAM_STR);
        $stm->bindParam(':comment', $comment, \PDO::PARAM_STR);
        $stm->bindParam(':payment', $payment, \PDO::PARAM_INT);
        $stm->bindParam(':callback', $callback, \PDO::PARAM_INT);
        $stm->execute();
        $orderNumber = $this->dbConnection->lastInsertId();

        return(['count'=>$count, 'orderNumber'=>$orderNumber]);
    }
}
