<?php

define('MYSQL_CONNECTION',mysql_connect('localhost','root','')); // Подключение к БД

mysql_select_db('tm'); // Выбор Базы

// Установка режима кодировки

//mysql_query("set character_set_connection=cp1251;",MYSQL_CONNECTION);

//mysql_query("set character_set_client=utf8;",MYSQL_CONNECTION);

//mysql_query("set character_set_results=utf8;",MYSQL_CONNECTION);