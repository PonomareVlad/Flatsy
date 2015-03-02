-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Мар 01 2015 г., 15:16
-- Версия сервера: 5.6.21
-- Версия PHP: 5.6.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `tm`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
`id` int(10) NOT NULL,
  `idtask` int(10) NOT NULL COMMENT 'ID задачи',
  `numbercom` int(10) NOT NULL COMMENT 'Порядковый номер комментария',
  `usercom` int(10) NOT NULL COMMENT 'Владелец комментария',
  `comment` varchar(1000) NOT NULL COMMENT 'Комментарий',
  `datacom` datetime(6) NOT NULL COMMENT 'Дата и время публикации'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Комментарии';

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
`idfile` int(10) NOT NULL COMMENT 'ID файла',
  `iduser` int(10) NOT NULL COMMENT 'ID пользователя',
  `namefile` varchar(100) NOT NULL COMMENT 'Имя файла',
  `timeload` datetime NOT NULL COMMENT 'Дата загрузки'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Таблица хранения информации о файлах';

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
`idgroup` int(10) NOT NULL COMMENT 'Номер группы',
  `namegroup` varchar(100) NOT NULL COMMENT 'Имя группы',
  `creator` int(10) NOT NULL COMMENT 'Создатель',
  `owner` int(10) NOT NULL COMMENT 'Владелец'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Таблица групп';

-- --------------------------------------------------------

--
-- Структура таблицы `project`
--

CREATE TABLE IF NOT EXISTS `project` (
`idproject` int(10) NOT NULL COMMENT 'ID проекта',
  `nameproject` varchar(100) NOT NULL COMMENT 'Имя проекта',
  `date_start` datetime NOT NULL COMMENT 'Дата старта',
  `date_finish` datetime NOT NULL COMMENT 'Дата финиша',
  `fact_finish` datetime NOT NULL COMMENT 'Фактическое завершение проекта',
  `initiator` int(10) NOT NULL COMMENT 'Инициатор',
  `parentproject` int(10) NOT NULL COMMENT 'Родительский проект',
  `description` varchar(1500) NOT NULL COMMENT 'Описание проекта'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Таблица проектов';

-- --------------------------------------------------------

--
-- Структура таблицы `task`
--

CREATE TABLE IF NOT EXISTS `task` (
`id` int(10) NOT NULL,
  `finished` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Статус задачи',
  `name` varchar(500) NOT NULL COMMENT 'Название/Описание',
  `initiator` int(10) NOT NULL COMMENT 'Инициатор',
  `executor` int(10) NOT NULL COMMENT 'Исполнитель',
  `description` varchar(1000) NOT NULL COMMENT 'Описание(необязательное поле)',
  `date_start` datetime NOT NULL COMMENT 'Дата старта',
  `date_finish` datetime NOT NULL COMMENT 'Дедлайн',
  `fact_finish` datetime NOT NULL COMMENT 'Время фактического завершения задачи',
  `parentask` int(10) NOT NULL COMMENT 'Родительская задача'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Таблица задач';

--
-- Структура таблицы `useringroup`
--

CREATE TABLE IF NOT EXISTS `useringroup` (
  `iduser` int(10) NOT NULL COMMENT 'ID юзера',
  `idgroup` int(10) NOT NULL COMMENT 'ID группы',
  `userlvl` int(10) NOT NULL COMMENT 'Уровень юзера',
  `statususer` int(10) NOT NULL COMMENT 'Статус юзера'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(10) NOT NULL,
  `lastname` varchar(20) NOT NULL COMMENT 'Фамилия',
  `firstname` varchar(20) NOT NULL COMMENT 'Имя',
  `patronymic` varchar(20) NOT NULL COMMENT 'Отчество',
  `password` varchar(32) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `last_act` datetime NOT NULL,
  `reg_date` datetime NOT NULL,
  `photo` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Структура таблицы `visgroups`
--

CREATE TABLE IF NOT EXISTS `visgroups` (
  `id` int(10) NOT NULL COMMENT 'Группа',
  `visidgroup` int(10) NOT NULL COMMENT 'Подчиненная группа'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `visprojectgroup`
--

CREATE TABLE IF NOT EXISTS `visprojectgroup` (
  `idgroup` int(10) NOT NULL COMMENT 'ID группы',
  `idproject` int(10) NOT NULL COMMENT 'ID проекта'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Сопровождающие задачу группы';

-- --------------------------------------------------------

--
-- Структура таблицы `visprojectuser`
--

CREATE TABLE IF NOT EXISTS `visprojectuser` (
  `iduser` int(10) NOT NULL COMMENT 'ID юзера',
  `idproject` int(10) NOT NULL COMMENT 'ID проекта'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Сопровождающие задачу пользователи';

-- --------------------------------------------------------

--
-- Структура таблицы `vistaskuser`
--

CREATE TABLE IF NOT EXISTS `vistaskuser` (
  `iduser` int(10) NOT NULL COMMENT 'ID юзера',
  `idtask` int(10) NOT NULL COMMENT 'ID задачи'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Сопровождающие задачу юзеры';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
 ADD PRIMARY KEY (`idfile`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
 ADD PRIMARY KEY (`idgroup`);

--
-- Индексы таблицы `project`
--
ALTER TABLE `project`
 ADD PRIMARY KEY (`idproject`);

--
-- Индексы таблицы `task`
--
ALTER TABLE `task`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `useringroup`
--
ALTER TABLE `useringroup`
 ADD PRIMARY KEY (`iduser`,`idgroup`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `visgroups`
--
ALTER TABLE `visgroups`
 ADD PRIMARY KEY (`id`,`visidgroup`);

--
-- Индексы таблицы `visprojectgroup`
--
ALTER TABLE `visprojectgroup`
 ADD PRIMARY KEY (`idgroup`,`idproject`);

--
-- Индексы таблицы `visprojectuser`
--
ALTER TABLE `visprojectuser`
 ADD PRIMARY KEY (`iduser`,`idproject`);

--
-- Индексы таблицы `vistaskuser`
--
ALTER TABLE `vistaskuser`
 ADD PRIMARY KEY (`iduser`,`idtask`);

