<?php

define('MODEL', ROOT.'system/model/model.php'); // Обявление модели
define('DB', ROOT.'system/model/db.php'); // Обявление базы данных
define('USER', ROOT.'system/model/user.php'); // Обявление базы данных
define('CUR_TMPL', 'default/');
define('DIR_TMPL', 'templates/'.CUR_TMPL);
define('TMPL', ROOT.DIR_TMPL);

require_once(ROOT.'system/config/db.conf.php'); // Подключаем конфиг БД
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
            $page = "index";
    }
}

require_once TMPL . $page.'.php';

define('TIME_END',microtime());
$time = TIME_END - TIME_START;
//echo $time*1000 . 'мс';






