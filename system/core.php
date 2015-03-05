<?php
session_start();

//define('MODEL', ROOT.'system/model/model.php'); // Обявление модели
//define('DB', ROOT.'system/model/db.php'); // Обявление базы данных
define('CUR_TMPL', 'default/');
define('DIR_TMPL', 'templates/'.CUR_TMPL);
define('TMPL', ROOT.DIR_TMPL);

// Подключение настроек
$cfg_dir = scandir(ROOT.'/system/config');
for ($i = 2; $i < count($cfg_dir); $i++){
    if (explode('.', $cfg_dir[$i])[count($cfg_dir[$i])+1] == 'php'){
        require_once(ROOT.'/system/config/'.$cfg_dir[$i]);
    }
}

require_once(ROOT . 'system/class.php'); // Подключаем классы

//require_once DB; // Подключение базы данных
//require_once MODEL;// Подключение Модели

$model = new Model(); // Вызов модели
require_once TMPL . 'index.php';