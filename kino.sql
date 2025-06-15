-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 07 2025 г., 18:51
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `kino`
--

-- --------------------------------------------------------

--
-- Структура таблицы `места`
--

CREATE TABLE `места` (
  `Идентификатор` int NOT NULL,
  `Идентификатор_сеанса` int NOT NULL,
  `Ряд` int NOT NULL,
  `Место` int NOT NULL,
  `Занято` tinyint(1) DEFAULT '0',
  `Идентификатор_бронирования` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `кинотеатры`
--

CREATE TABLE `кинотеатры` (
  `Идентификатор` int NOT NULL,
  `Название` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Адрес` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Телефон` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `кинотеатры`
--

INSERT INTO `кинотеатры` (`Идентификатор`, `Название`, `Адрес`, `Телефон`) VALUES
(1, 'Киномакс', 'ул. Пушкина, 10', '+7 (XXX) XXX-XXXX'),
(2, 'Синема Парк', 'пр. Ленина, 25', '+7 (XXX) XXX-XXXX'),
(3, 'КиноСтар', 'ул. Гагарина, 5', '+7 (XXX) XXX-XXXX'),
(4, 'Планета Кино', 'пр. Кирова, 15', '+7 (XXX) XXX-XXXX'),
(5, 'Формула Кино', 'ул. Советская, 30', '+7 (XXX) XXX-XXXX');

-- --------------------------------------------------------

--
-- Структура таблицы `клиенты`
--

CREATE TABLE `клиенты` (
  `Идентификатор` int NOT NULL,
  `Имя` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Фамилия` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Электронная_почта` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Телефон` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Логин` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Пароль` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `клиенты`
--

INSERT INTO `клиенты` (`Идентификатор`, `Имя`, `Фамилия`, `Электронная_почта`, `Телефон`, `Логин`, `Пароль`) VALUES
(11, 'Ivan', 'Petrov', '1234@mail.ru', '9111223333', 'ivan', '81dc9bdb52d04dc20036dbd8313ed055'),
(12, 'Danil', 'Starilov', 'kreazmat@mail.ru', '89021440740', 'Drago', '827ccb0eea8a706c4c34a16891f84e7b'),
(13, 'Никита', 'Мамедов', '1234@mail.ru', '9111223333', 'kent228', '81dc9bdb52d04dc20036dbd8313ed055');

-- --------------------------------------------------------

--
-- Структура таблицы `администраторы`
--

CREATE TABLE `администраторы` (
  `Идентификатор` int NOT NULL,
  `Имя` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Фамилия` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Электронная_почта` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Телефон` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Логин` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Пароль` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `администраторы`
--

INSERT INTO `администраторы` (`Идентификатор`, `Имя`, `Фамилия`, `Электронная_почта`, `Телефон`, `Логин`, `Пароль`) VALUES
(1, 'Иван', 'Иванов', 'ivanov@example.com', '1234567890', 'admin1', 'password1'),
(2, 'Данил', 'Старилов', 'kreazmat@mail.ru', '89021440740', 'admin', '1234');

-- --------------------------------------------------------

--
-- Структура таблицы `бронирования`
--

CREATE TABLE `бронирования` (
  `Идентификатор` int NOT NULL,
  `Идентификатор_клиента` int DEFAULT NULL,
  `Идентификатор_сеанса` int DEFAULT NULL,
  `Количество_мест` int DEFAULT NULL,
  `Ряд` int DEFAULT NULL,
  `Место` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `бронирования`
--

INSERT INTO `бронирования` (`Идентификатор`, `Идентификатор_клиента`, `Идентификатор_сеанса`, `Количество_мест`, `Ряд`, `Место`) VALUES
(8, 11, 5, 1, 1, 1),
(9, 11, 4, 1, 1, 1),
(10, 11, 5, 1, 1, 2),
(11, 11, 5, 1, 8, 8);

-- --------------------------------------------------------

--
-- Структура таблицы `сеансы`
--

CREATE TABLE `сеансы` (
  `Идентификатор` int NOT NULL,
  `Идентификатор_фильма` int DEFAULT NULL,
  `Идентификатор_кинотеатра` int DEFAULT NULL,
  `Время_начала` datetime DEFAULT NULL,
  `Цена` int NOT NULL DEFAULT '500'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `сеансы`
--

INSERT INTO `сеансы` (`Идентификатор`, `Идентификатор_фильма`, `Идентификатор_кинотеатра`, `Время_начала`, `Цена`) VALUES
(4, 6, 2, '2025-05-14 17:55:00', 600),
(5, 8, 1, '2025-05-14 18:00:00', 400),
(6, 10, 3, '2025-05-14 17:00:00', 250),
(8, 11, 4, '2025-05-14 18:00:00', 300),
(9, 6, 1, '2025-05-14 18:00:00', 500),
(10, 6, 5, '2025-05-14 18:00:00', 900),
(11, 8, 3, '2025-05-14 18:00:00', 110),
(13, 11, 1, '2025-05-14 14:30:00', 500);

-- --------------------------------------------------------

--
-- Структура таблицы `фильмы`
--

CREATE TABLE `фильмы` (
  `Идентификатор` int NOT NULL,
  `Название` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Режиссер` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Год_выпуска` int DEFAULT NULL,
  `Описание` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Постер` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `фильмы`
--

INSERT INTO `фильмы` (`Идентификатор`, `Название`, `Режиссер`, `Год_выпуска`, `Описание`, `Постер`) VALUES
(6, 'Красный шёлк', 'Андрей Волгин', 2025, '1927 год. Транссибирский экспресс. Через всю Россию перевозят секретные документы, которые определят будущее СССР и Китая. Под видом обычных пассажиров скрываются иностранные разведчики и настоящие головорезы, готовые на всё ради документов. Молодому красноармейцу и бывшему царскому агенту приходится объединиться, чтобы раскрыть общего врага.', 'https://upload.wikimedia.org/wikipedia/ru/8/82/%D0%9A%D1%80%D0%B0%D1%81%D0%BD%D1%8B%D0%B9_%D1%88%D1%91%D0%BB%D0%BA.jpg'),
(8, 'Опустошение', 'Гарет Эванс', 2025, 'Расследуя убийство молодого криминального босса и его окружения, детектив Уокер выясняет, что в момент преступления там находился Чарли — сын кандидата в мэры, с которым у копа давние коррупционные отношения. Уокер пытается разыскать ставшего главным подозреваемым Чарли и его девушку, прежде чем до них доберутся его коллеги, а из Азии прибывает глава триады и мать убитого с твёрдым намерением покарать виновных.', 'https://upload.wikimedia.org/wikipedia/ru/thumb/4/48/Havoc_%28film%2C_2025%29.jpg/330px-Havoc_%28film%2C_2025%29.jpg'),
(10, 'Злой город', 'Константин Буслов', 2025, '1238 год. Многочисленное войско Золотой Орды в течение двух месяцев атаковало маленький русский город Козельск. Тысячи погибших монгольских воинов и использованных осадных орудий, но город всё стоял, показывая врагу несокрушимость и твёрдость русского духа. После этой битвы хан Батый повелел навеки забыть его имя и отныне именовать Злым городом.', 'https://upload.wikimedia.org/wikipedia/ru/thumb/6/60/%D0%97%D0%BB%D0%BE%D0%B9_%D0%B3%D0%BE%D1%80%D0%BE%D0%B4_%D1%84%D0%B8%D0%BB%D1%8C%D0%BC.webp/330px-%D0%97%D0%BB%D0%BE%D0%B9_%D0%B3%D0%BE%D1%80%D0%BE%D0%B4_%D1%84%D0%B8%D0%BB%D1%8C%D0%BC.webp.png'),
(11, 'Аларум', 'Майкл Полиш', 2025, 'Джо и Лора, два бывших секретных агента ЦРУ, проводят романтический отпуск на острове, где они становятся свидетелями авиакатастрофы. Среди обломков оказывается флешка с секретной информацией, забрав которую пара моментально становится целью для всех спецслужб мира. По их следам ЦРУ отправляет самого хладнокровного агента, Честера, который должен во что бы то ни стало убить предателей.', 'https://upload.wikimedia.org/wikipedia/en/8/86/Alarum_Poster.jpg');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `кинотеатры`
--
ALTER TABLE `кинотеатры`
  ADD PRIMARY KEY (`Идентификатор`);

--
-- Индексы таблицы `клиенты`
--
ALTER TABLE `клиенты`
  ADD PRIMARY KEY (`Идентификатор`);

--
-- Индексы таблицы `администраторы`
--
ALTER TABLE `администраторы`
  ADD PRIMARY KEY (`Идентификатор`);

--
-- Индексы таблицы `бронирования`
--
ALTER TABLE `бронирования`
  ADD PRIMARY KEY (`Идентификатор`),
  ADD KEY `Идентификатор_клиента` (`Идентификатор_клиента`),
  ADD KEY `Идентификатор_сеанса` (`Идентификатор_сеанса`);

--
-- Индексы таблицы `сеансы`
--
ALTER TABLE `сеансы`
  ADD PRIMARY KEY (`Идентификатор`),
  ADD KEY `Идентификатор_фильма` (`Идентификатор_фильма`),
  ADD KEY `Идентификатор_кинотеатра` (`Идентификатор_кинотеатра`);

--
-- Индексы таблицы `фильмы`
--
ALTER TABLE `фильмы`
  ADD PRIMARY KEY (`Идентификатор`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `кинотеатры`
--
ALTER TABLE `кинотеатры`
  MODIFY `Идентификатор` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `клиенты`
--
ALTER TABLE `клиенты`
  MODIFY `Идентификатор` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `бронирования`
--
ALTER TABLE `бронирования`
  MODIFY `Идентификатор` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `сеансы`
--
ALTER TABLE `сеансы`
  MODIFY `Идентификатор` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `фильмы`
--
ALTER TABLE `фильмы`
  MODIFY `Идентификатор` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `бронирования`
--
ALTER TABLE `бронирования`
  ADD CONSTRAINT `бронирования_ibfk_1` FOREIGN KEY (`Идентификатор_клиента`) REFERENCES `клиенты` (`Идентификатор`),
  ADD CONSTRAINT `бронирования_ibfk_2` FOREIGN KEY (`Идентификатор_сеанса`) REFERENCES `сеансы` (`Идентификатор`);

--
-- Ограничения внешнего ключа таблицы `сеансы`
--
ALTER TABLE `сеансы`
  ADD CONSTRAINT `сеансы_ibfk_1` FOREIGN KEY (`Идентификатор_фильма`) REFERENCES `фильмы` (`Идентификатор`),
  ADD CONSTRAINT `сеансы_ibfk_2` FOREIGN KEY (`Идентификатор_кинотеатра`) REFERENCES `кинотеатры` (`Идентификатор`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
