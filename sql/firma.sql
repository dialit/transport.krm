-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июл 22 2017 г., 21:23
-- Версия сервера: 5.5.50-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `final`
--

-- --------------------------------------------------------

--
-- Структура таблицы `firma`
--

CREATE TABLE IF NOT EXISTS `firma` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `adress` varchar(100) NOT NULL,
  `manager` varchar(200) NOT NULL,
  `tel` varchar(250) NOT NULL,
  `rej_raboty` varchar(50) NOT NULL,
  `vr_raboty` varchar(50) NOT NULL,
  `marshruty` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `firma`
--

INSERT INTO `firma` (`id`, `name`, `adress`, `manager`, `tel`, `rej_raboty`, `vr_raboty`, `marshruty`) VALUES
(1, 'ООО ЮМВОСА', '84313, Донецкая обл., г. Краматорск, ул. Марины Расковой, 5', 'Директор - Рогоза Владимир Андреевич', '(06264)7 73 14', 'Пн Вт Ср Чт Пт', '10:00 - 17:00', 'Маршрутное такси: 2,3,3A,4,8,8A,9,9A,10,12,14,14A,16,20,22,25'),
(2, 'ЧАО "Краматорское АТП-114"', 'г. Краматорск, ул. Олексы Тихого, 6 http://atp11410.com.ua', 'Директор - Дубовый Сергей Александрович', 'Приемная: (06264) 7 71 29.\r\nДиспечерская служба: (06264) 5 45 36; (050) 273 83 26.', 'Пн Вт Ср Чт Пт', '10:00 - 17:00', 'Маршрутное такси: 1,5,6,7,15,17,17A,18,19,21,23,29,30,31,32'),
(3, 'КП "Краматорское трамвайно-троллейбусное управление"', '84323, Донецкая обл., г. Краматорск, ул. Кима, 103', 'Директор - Шацкий Сергей Петрович\r\nРуководитель - Бороха Андрей Степанович\r\nБухгалтер - Спасибухова Елена Викторовна', '(06264) 7 14 72', 'Пн Вт Ср Чт Пт', '10:00 - 17:00', 'Автобус: 2А,5А.\r\nТроллейбус: 1,2,3,5,6.');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
