<?php

header("HTTP/1.0 200 OK"); // Возвращаем успешное состояние при любых запросах
header("Access-Control-Allow-Origin: *"); // Разрешаем CORS

define('VERSION',70);

if($_SERVER['HTTP_HOST']=='localhost'){
    readfile(ROOT.'Flatsy.html');
    exit;
}

if(!defined('CORE')) {

    require_once(ROOT . 'system/func.php'); // Подключаем вспомогательные функции

// Подключение настроек
    $cfg_dir = scandir(ROOT . '/system/config');
    for ($i = 2; $i < count($cfg_dir); $i++) {
        if (explode('.', $cfg_dir[$i])[count($cfg_dir[$i]) + 1] == 'php') {
            require_once(ROOT . '/system/config/' . $cfg_dir[$i]);
        }
    }

// Подключение классов
    $class_dir = scandir(ROOT . '/system/class');
    for ($i = 2; $i < count($class_dir); $i++) {
        if (explode('.', $class_dir[$i])[count($class_dir[$i])] == 'php') {
            require_once(ROOT . '/system/class/' . $class_dir[$i]);
        }
    }

    //dbg_clean();
    
    DB::update('sessions',['closed'=>1],'closed=0 AND last_act<"'.date('Y-m-d H:i:s',mktime(date("H"), date("i")-15, date("s"), date("m")  , date("d"), date("Y"))).'"');

    User::init(); // Запуск подсистемы пользователей и проверка статуса авторизации

    if (!defined('AJAX')) { // Генерация интерфейса включена

        //require_once LANG; // Подключаем язык

        $link = explode('/', $_SERVER['REQUEST_URI']);
        if (!empty($link[1])) {
            $page = ($link[1] == 'auth' || $link[1] == 'tasks' || $link[1] == 'projects' || $link[1] == 'groups' || $link[1] == 'lk' || $link[1] == 'reg') ? $link[1] : 'tasks';
            define('PAGE', strtolower($page));
        }

        //require_once TMPL . 'index.php'; // Подключаем макет странцы
        readfile(ROOT.'Flatsy.html');
    }
}