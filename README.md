### Loftschool.com выпускной проект №1 - Бургерная

- Доступ в админ панель сайта /src/admin.php
- Исходящие письма складываются в /mail
- Для работы необходимо создать файл с натройками подключения к БД /src/config.php

```php
    <?php
    const HOST = 'host';
    const DBNAME = 'database_name';
    const USER = 'username';
    const PASS = 'password';
```

- Структура базы данных

```

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(5) NOT NULL,
  `userId` int(5) NOT NULL,
  `street` varchar(255) NOT NULL,
  `home` varchar(255) NOT NULL,
  `part` varchar(255) NOT NULL,
  `appt` varchar(255) NOT NULL,
  `floor` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `payment` int(1) NOT NULL,
  `callback` int(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(5) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `orders`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;


ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
  
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

```