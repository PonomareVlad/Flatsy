<?php

require_once(ROOT.'system/config/db.conf.php'); // Подключаем конфиг БД
require_once(ROOT.'system/config/path.conf.php'); // Подключаем конфиг class

require_once DB; // Подключение базы данных
require_once MODEL;// Подключение Модели
require_once USER;// Подключение юзеров


$Model = new Model();
$User = new User();
if($_POST){
    if($_POST["reg_submit"]) $User->registration($_POST);
    exit;
}
$cur_page = $_GET['page'];
if(!isset($_COOKIE['HASH'])){
    switch($cur_page){
        case "auth":
            $page = "auth";
            $GLOBALS['title_page'] = "Авторизация";
            break;
        case "reg":
            $page = "reg";
            $GLOBALS['title_page'] = "Регистрация";
            break;
        default:
            $GLOBALS['title_page'] == "";
            $page = "main";
    }
}

require_once(ROOT.'system/func.php'); // Подключаем вспомогательные функции
require_once TMPL .'index.php';

define('TIME_END',microtime());
$time = TIME_END - TIME_START;
//echo $time*1000 . 'мс';






