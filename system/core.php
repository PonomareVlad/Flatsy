<?php

require_once(ROOT.'system/config/db.conf.php'); // Подключаем конфиг БД
require_once(ROOT.'system/config/path.conf.php'); // Подключаем конфиг gentq
require_once(ROOT . 'system/func.php'); // Подключаем вспомогательные функции

require_once DB; // Подключение базы данных
require_once MODEL;// Подключение Модели
require_once USER;// Подключение Модели

$User = new User();
$User->init();

$Model = new Model(); // Вызов модели
$cur_page = $_GET['page'];
if(!isset($_COOKIE['HASH'])){
    switch($cur_page){
        case "auth":
            $page = "auth";
            break;
        case "reg":
            $page = "reg";
            break;
        default:
            $page = "main";
    }
}

require_once TMPL .'index.php';

define('TIME_END',microtime());
$time = TIME_END - TIME_START;
//echo $time*1000 . 'мс';






