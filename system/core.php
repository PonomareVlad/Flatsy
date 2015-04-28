<?php

header("HTTP/1.0 200 OK"); // Возвращаем успешное состояние при любых запросах
header("Access-Control-Allow-Origin: *"); // Разрешаем CORS

define('VERSION',52);

require_once(ROOT.'system/func.php'); // Подключаем вспомогательные функции

// Подключение настроек
$cfg_dir = scandir(ROOT.'/system/config');
for ($i = 2; $i < count($cfg_dir); $i++){
    if (explode('.', $cfg_dir[$i])[count($cfg_dir[$i])+1] == 'php'){
        require_once(ROOT.'/system/config/'.$cfg_dir[$i]);
    }
}

// Подключение классов
$class_dir = scandir(ROOT.'/system/class');
for ($i = 2; $i < count($class_dir); $i++){
    if (explode('.', $class_dir[$i])[count($class_dir[$i])] == 'php'){
        require_once(ROOT.'/system/class/'.$class_dir[$i]);
    }
}

User::init(); // Запуск подсистемы пользователей и проверка статуса авторизации

if(!defined('AJAX')) { // Генерация интерфейса включена

    //require_once LANG; // Подключаем язык

    $link = explode('/', $_SERVER['REQUEST_URI']);
    if (!empty($link[1])) {
        $page=($link[1]=='auth'||$link[1]=='tasks'||$link[1]=='projects'||$link[1]=='groups'||$link[1]=='lk'||$link[1]=='reg')?$link[1]:'tasks';
        define('PAGE',strtolower($page));
    }

    require_once TMPL.'index.php'; // Подключаем макет странцы

}