-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 10 2024 г., 23:39
-- Версия сервера: 8.0.30
-- Версия PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `comments`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id_comment` int NOT NULL,
  `id_user` int NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id_comment`, `id_user`, `comment`, `created_at`, `updated_at`) VALUES
(2, 5, 'sdfgdfgdfgdfg', '2024-12-31 21:00:00', '0000-00-00 00:00:00'),
(3, 1, 'sdfgdfgdfgdfg', '2024-12-31 21:00:00', '0000-00-00 00:00:00'),
(4, 4, 'sdfgdfgdfg443534dfgdfg', '2024-12-31 21:00:00', '0000-00-00 00:00:00'),
(5, 5, 'sdfgdfgdfghdfgdfgdfg', '2024-12-31 21:00:00', '0000-00-00 00:00:00'),
(6, 3, 'sdfgdfgdfhdfvcdfgdfg', '2024-12-31 21:00:00', '0000-00-00 00:00:00'),
(7, 1, 'sdfgdfgdfgdfg', '2024-12-31 21:00:00', '0000-00-00 00:00:00'),
(8, 3, 'sdfcvbcvbgdfgdsgdfgfgdfg', '2024-12-31 21:00:00', '0000-00-00 00:00:00'),
(17, 13, 'i\'m stay here ', '2024-04-10 14:46:57', '2024-04-10 14:47:21');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `second_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `first_name`, `second_name`, `email`, `password`) VALUES
(1, 'test', 'tester', 'tt', 'ssss'),
(2, 'dima', '45345', 'flaserhost@yandex.ru', '$2y$10$vHN1m5v8JInCAsK4dw7SVO8CBI5m407Z2pF2vlVPFzDa8Sg9Ad6cq'),
(3, 'Иван', 'Грозный', 'ttt', 'ssss'),
(4, 'вап', '34е', 'ttt', 'ssss'),
(5, 'сми', '4566', 'xcbttt', 'ssss'),
(6, 'парап', 'тми', 'ttt', 'vcbssss'),
(7, 'мит', '23ва', 'ttt', 'ssss'),
(13, 't555', 'yjhyy', 'flaserhost@gmail.com', '$2y$10$8DAMe7ezXQpz/gmPFJN5U.Scs00cu7XBvImtc7SAOV9iYJrKAZ..a');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `id_user` (`id_user`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comment` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
