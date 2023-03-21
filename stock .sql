-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 21 2023 г., 11:09
-- Версия сервера: 10.4.27-MariaDB
-- Версия PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `stock`
--

DELIMITER $$
--
-- Процедуры
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `pGetCountCars` ()   SELECT COUNT(id) AS count FROM car$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `car`
--

CREATE TABLE `car` (
  `id` int(11) NOT NULL,
  `mark` varchar(45) DEFAULT NULL,
  `owner` varchar(45) DEFAULT NULL,
  `Mweight` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `car`
--

INSERT INTO `car` (`id`, `mark`, `owner`, `Mweight`) VALUES
(1, 'ford', 'Kolya', '3'),
(2, 'mercedeses', 'Tolya', '2');

-- --------------------------------------------------------

--
-- Структура таблицы `devilery`
--

CREATE TABLE `devilery` (
  `id` int(11) NOT NULL,
  `id_storage` int(11) NOT NULL,
  `id_Product` int(11) NOT NULL,
  `id_Car` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `devilery`
--

INSERT INTO `devilery` (`id`, `id_storage`, `id_Product`, `id_Car`) VALUES
(3, 3, 3, 2),
(4, 1, 1, 1),
(5, 2, 3, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `market`
--

CREATE TABLE `market` (
  `id` int(11) NOT NULL,
  `id_sclad` int(11) NOT NULL,
  `id_car` int(11) NOT NULL,
  `id_product` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `market`
--

INSERT INTO `market` (`id`, `id_sclad`, `id_car`, `id_product`) VALUES
(1, 1, 1, 1),
(2, 3, 2, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `polygon`
--

CREATE TABLE `polygon` (
  `id` int(11) NOT NULL,
  `sclad_title` varchar(45) NOT NULL,
  `sclad_owner` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `polygon`
--

INSERT INTO `polygon` (`id`, `sclad_title`, `sclad_owner`) VALUES
(1, 'three', 'Vladimir');

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `number` varchar(45) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `vencode` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `number`, `title`, `vencode`) VALUES
(1, '300', 'three', 'fff70126'),
(2, '500', 'potato', 'fff43265'),
(3, '400', 'tomat', 'fff435742');

-- --------------------------------------------------------

--
-- Структура таблицы `sclad`
--

CREATE TABLE `sclad` (
  `id` int(11) NOT NULL,
  `Title` varchar(45) DEFAULT NULL,
  `Owner` varchar(45) DEFAULT NULL,
  `capacity` varchar(45) DEFAULT NULL,
  `Shelf_life` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `sclad`
--

INSERT INTO `sclad` (`id`, `Title`, `Owner`, `capacity`, `Shelf_life`) VALUES
(1, 'potato', 'Vladimir', '1000', '2020-01-17'),
(2, 'three', 'Sergey', '1000', '2020-01-19'),
(3, 'tomat', 'jack', '1000', '2021-10-10'),
(4, NULL, NULL, NULL, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `devilery`
--
ALTER TABLE `devilery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `storage_idx` (`id_storage`),
  ADD KEY `product_idx` (`id_Product`),
  ADD KEY `car_idx` (`id_Car`);

--
-- Индексы таблицы `market`
--
ALTER TABLE `market`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sklad_idx` (`id_sclad`),
  ADD KEY `product_idx` (`id_product`),
  ADD KEY `car_idx` (`id_car`);

--
-- Индексы таблицы `polygon`
--
ALTER TABLE `polygon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sklad_idx` (`sclad_title`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sclad`
--
ALTER TABLE `sclad`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `car`
--
ALTER TABLE `car`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `devilery`
--
ALTER TABLE `devilery`
  ADD CONSTRAINT `car` FOREIGN KEY (`id_Car`) REFERENCES `car` (`id`),
  ADD CONSTRAINT `product` FOREIGN KEY (`id_Product`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `storage` FOREIGN KEY (`id_storage`) REFERENCES `sclad` (`id`);

--
-- Ограничения внешнего ключа таблицы `market`
--
ALTER TABLE `market`
  ADD CONSTRAINT `c` FOREIGN KEY (`id_car`) REFERENCES `car` (`id`),
  ADD CONSTRAINT `p` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `s` FOREIGN KEY (`id_sclad`) REFERENCES `sclad` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
