<?php
defined('ROOT') or header('Location: /');
//error_reporting(1);

require_once(ROOT . 'system/func.php'); // Подключаем вспомогательные функции

// Подключение настроек
$cfg_dir = scandir(ROOT.'/system/config');
for ($i = 2; $i < count($cfg_dir); $i++){
    if (explode('.', $cfg_dir[$i])[count($cfg_dir[$i])+1] == 'php'){
        require_once(ROOT.'/system/config/'.$cfg_dir[$i]);
    }
}

require_once(ROOT.'system/class/User.php');
User::init(); // Запуск подсистемы пользователей и проверка статуса авторизации

if(defined('CORE_MODE')&&CORE_MODE=='light'){ // Сокращенный режим работы ядра (Для Ajax)

    error_reporting(0);

}else { // Полный режим работы ядра

    // Подключение классов
    $class_dir = scandir(ROOT.'/system/class');
    for ($i = 2; $i < count($class_dir); $i++){
        if (explode('.', $class_dir[$i])[count($class_dir[$i])] == 'php'){
            require_once(ROOT.'/system/class/'.$class_dir[$i]);
        }
    }



    //define('MODEL', ROOT.'system/model/model.php'); // Обявление модели
    //define('DB', ROOT.'system/model/MySQL.php'); // Обявление базы данных

    //require_once DB; // Подключение базы данных
    //require_once MODEL;// Подключение Модели

    define('CUR_TMPL', 'default/');
    define('DIR_TMPL', 'templates/' . CUR_TMPL);
    define('TMPL', ROOT . DIR_TMPL);

    MVC::start(); // Запуск MVC
    $model = new Model(); // Вызов модели
    MVC::view(); // Процесс вывода
}