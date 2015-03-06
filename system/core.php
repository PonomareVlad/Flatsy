<?php
defined('ROOT') or header('Location: /');
error_reporting(1);

// Подключение настроек
$cfg_dir = scandir(ROOT.'/system/config');
for ($i = 2; $i < count($cfg_dir); $i++){
    if (explode('.', $cfg_dir[$i])[count($cfg_dir[$i])+1] == 'php'){
        require_once(ROOT.'/system/config/'.$cfg_dir[$i]);
    }
}

require_once(ROOT . 'system/class.php'); // Подключаем классы

User::init();

if(defined('CORE')&&CORE=='light'){

    error_reporting(0);

}else {
    $head = '<title>EasyTM</title><meta charset="utf-8" /><script src="/js/main.js"></script>';
    // MVC

    //define('MODEL', ROOT.'system/model/model.php'); // Обявление модели
    //define('DB', ROOT.'system/model/db.php'); // Обявление базы данных

    //require_once DB; // Подключение базы данных
    //require_once MODEL;// Подключение Модели

    define('CUR_TMPL', 'default/');
    define('DIR_TMPL', 'templates/' . CUR_TMPL);
    define('TMPL', ROOT . DIR_TMPL);

    Route::start();
    $model = new Model(); // Вызов модели
    Route::view();
}