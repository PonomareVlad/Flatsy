
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth` (
  `hash` varchar(32) NOT NULL COMMENT 'Хэш юзера',
  `iduser` int(10) NOT NULL COMMENT 'ID юзера',
  PRIMARY KEY (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `auth` WRITE;
/*!40000 ALTER TABLE `auth` DISABLE KEYS */;

/*!40000 ALTER TABLE `auth` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `authkey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authkey` (
  `idkey` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `key` varchar(19) NOT NULL COMMENT 'Ключ',
  `iduser` int(10) NOT NULL COMMENT 'Кем активирован',
  `whoinvite` varchar(30) NOT NULL COMMENT 'Кому был выдан',
  `datatime` datetime NOT NULL COMMENT 'Дата активации',
  PRIMARY KEY (`idkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ключи активации';
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `authkey` WRITE;
/*!40000 ALTER TABLE `authkey` DISABLE KEYS */;
/*!40000 ALTER TABLE `authkey` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idobject` int(10) NOT NULL COMMENT 'ID объекта содержащего комментарий',
  `type` varchar(30) NOT NULL COMMENT 'Тип объекта содержащего комментарий',
  `numbercom` int(10) NOT NULL COMMENT 'Порядковый номер комментария',
  `usercom` int(10) NOT NULL COMMENT 'Владелец комментария',
  `comment` varchar(1000) NOT NULL COMMENT 'Комментарий',
  `datacom` datetime NOT NULL COMMENT 'Дата и время публикации',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='Комментарии';
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;

/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
  `idfile` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID файла',
  `iduser` int(10) NOT NULL COMMENT 'ID пользователя',
  `namefile` varchar(100) NOT NULL COMMENT 'Имя файла',
  `path` varchar(150) NOT NULL,
  `timeload` datetime NOT NULL COMMENT 'Дата загрузки',
  `type` varchar(30) NOT NULL,
  `object` int(11) NOT NULL,
  PRIMARY KEY (`idfile`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='Таблица хранения информации о файлах';
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;

/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `idgroup` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Номер группы',
  `namegroup` varchar(100) NOT NULL COMMENT 'Имя группы',
  `creator` int(10) NOT NULL COMMENT 'Создатель',
  `owner` int(10) NOT NULL COMMENT 'Владелец',
  PRIMARY KEY (`idgroup`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='Таблица групп';
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;

/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `invite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invite` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Счетчик',
  `creator` int(10) DEFAULT NULL COMMENT 'Инициатор запроса',
  `iduser` int(10) DEFAULT NULL COMMENT 'Кого приглашают',
  `hash` varchar(64) NOT NULL,
  `type` varchar(64) NOT NULL,
  `value` int(11) DEFAULT NULL,
  `status` int(10) DEFAULT NULL COMMENT 'Статус',
  `date` datetime DEFAULT NULL COMMENT 'Дата приглашения',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='Инвайт в группу';
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `invite` WRITE;
/*!40000 ALTER TABLE `invite` DISABLE KEYS */;

/*!40000 ALTER TABLE `invite` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `invitegroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invitegroup` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `iduser` int(10) NOT NULL COMMENT 'ID пользователя',
  `idgroup` int(10) NOT NULL COMMENT 'ID группы',
  `idinviteuser` int(10) NOT NULL COMMENT 'ID приглашающего юзера',
  `status` int(10) NOT NULL COMMENT 'статус пользователя',
  `date` datetime NOT NULL COMMENT 'дата приглашения',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Таблица приглашений в группы';
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `invitegroup` WRITE;
/*!40000 ALTER TABLE `invitegroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `invitegroup` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `idnotification` int(10) NOT NULL AUTO_INCREMENT,
  `iduser` int(10) NOT NULL COMMENT 'ID пользователя',
  `type` varchar(50) NOT NULL COMMENT 'Тип (текстовое поле)',
  `value` int(10) NOT NULL COMMENT 'Значение (число)',
  PRIMARY KEY (`idnotification`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='Информация об обновлении данных';
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;

/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `idproject` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID проекта',
  `nameproject` varchar(100) NOT NULL COMMENT 'Имя проекта',
  `date_start` datetime NOT NULL COMMENT 'Дата старта',
  `date_finish` datetime NOT NULL COMMENT 'Дата финиша',
  `fact_finish` datetime NOT NULL COMMENT 'Фактическое завершение проекта',
  `initiator` int(10) NOT NULL COMMENT 'Инициатор',
  `parentproject` int(10) NOT NULL COMMENT 'Родительский проект',
  `description` varchar(1500) DEFAULT NULL COMMENT 'Описание проекта',
  PRIMARY KEY (`idproject`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='Таблица проектов';
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;

/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `hash` varchar(32) NOT NULL,
  `iduser` int(8) NOT NULL,
  `date` datetime NOT NULL,
  `last_act` datetime NOT NULL,
  `ip` text NOT NULL,
  `provider` text NOT NULL,
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;

/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `task` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `idproject` int(10) NOT NULL COMMENT 'Привязка к проекту',
  `finished` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Статус задачи',
  `name` varchar(500) NOT NULL COMMENT 'Название/Описание',
  `initiator` int(10) NOT NULL COMMENT 'Инициатор',
  `executor` int(10) NOT NULL COMMENT 'Исполнитель',
  `description` varchar(1000) DEFAULT NULL COMMENT 'Описание(необязательное поле)',
  `date_start` datetime NOT NULL COMMENT 'Дата старта',
  `date_finish` datetime NOT NULL COMMENT 'Дедлайн',
  `fact_finish` datetime NOT NULL COMMENT 'Время фактического завершения задачи',
  `parentask` int(10) NOT NULL COMMENT 'Родительская задача',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='Таблица задач';
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `task` WRITE;
/*!40000 ALTER TABLE `task` DISABLE KEYS */;

/*!40000 ALTER TABLE `task` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `useringroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `useringroup` (
  `iduser` int(10) NOT NULL COMMENT 'ID юзера',
  `idgroup` int(10) NOT NULL COMMENT 'ID группы',
  `userlvl` int(10) NOT NULL COMMENT 'Уровень юзера',
  `statususer` int(10) NOT NULL COMMENT 'Статус юзера',
  PRIMARY KEY (`iduser`,`idgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `useringroup` WRITE;
/*!40000 ALTER TABLE `useringroup` DISABLE KEYS */;

/*!40000 ALTER TABLE `useringroup` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(20) NOT NULL COMMENT 'Фамилия',
  `firstname` varchar(20) NOT NULL COMMENT 'Имя',
  `patronymic` varchar(20) NOT NULL COMMENT 'Отчество',
  `password` varchar(32) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `last_act` datetime NOT NULL,
  `reg_date` datetime NOT NULL,
  `photo` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `visgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visgroups` (
  `id` int(10) NOT NULL COMMENT 'Группа',
  `visidgroup` int(10) NOT NULL COMMENT 'Подчиненная группа',
  PRIMARY KEY (`id`,`visidgroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `visgroups` WRITE;
/*!40000 ALTER TABLE `visgroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `visgroups` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `visprojectgroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visprojectgroup` (
  `idgroup` int(10) NOT NULL COMMENT 'ID группы',
  `idproject` int(10) NOT NULL COMMENT 'ID проекта',
  PRIMARY KEY (`idgroup`,`idproject`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Сопровождающие задачу группы';
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `visprojectgroup` WRITE;
/*!40000 ALTER TABLE `visprojectgroup` DISABLE KEYS */;

/*!40000 ALTER TABLE `visprojectgroup` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `visprojectuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visprojectuser` (
  `iduser` int(10) NOT NULL COMMENT 'ID юзера',
  `idproject` int(10) NOT NULL COMMENT 'ID проекта',
  PRIMARY KEY (`iduser`,`idproject`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Сопровождающие задачу пользователи';
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `visprojectuser` WRITE;
/*!40000 ALTER TABLE `visprojectuser` DISABLE KEYS */;

/*!40000 ALTER TABLE `visprojectuser` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `vistaskuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vistaskuser` (
  `iduser` int(10) NOT NULL COMMENT 'ID юзера',
  `idtask` int(10) NOT NULL COMMENT 'ID задачи',
  PRIMARY KEY (`iduser`,`idtask`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Сопровождающие задачу юзеры';
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `vistaskuser` WRITE;
/*!40000 ALTER TABLE `vistaskuser` DISABLE KEYS */;
/*!40000 ALTER TABLE `vistaskuser` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

