<?php

header("HTTP/1.0 200 OK"); // Возвращаем успешное состояние при любых запросах

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

    require_once LANG; // Подключаем язык

    //$cur_page=false;
    $link = explode('/', $_SERVER['REQUEST_URI']); // "http://site.com/reg"
    if (!empty($link[1])) {
        //$cur_page = strtolower($link[1]); // "reg"
        define('PAGE',strtolower($link[1]));
    }

    /*
    if (!defined('USER_ID')) { // Если нет авторизации
        foreach ($PAGES as $page_arr) {
            if ($cur_page == $page_arr[0]) {
                if ($page_arr[2] == false) {
                    $page = $page_arr[0];
                    $GLOBALS['title_page'] = $page_arr[1];
                    break;
                }
            }
        }
        if (!isset($page)) {
            $page = 'auth';
            $GLOBALS['title_page'] = 'Авторизация';
        } // Если неверный Url
    } else { // Если есть авторизация
        foreach ($PAGES as $page_arr) {
            if ($cur_page == $page_arr[0]) {
                if ($page_arr[2] == true) {
                    $page = $page_arr[0];
                    $GLOBALS['title_page'] = $page_arr[1];
                    break;
                }
            }
        }
        if (!isset($page)) {
            $page = 'main';
            $GLOBALS['title_page'] = '';
        } // Если неверный Url
    }
    */

    require_once TMPL.'index.php'; // Подключаем макет странцы

}




