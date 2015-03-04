<?php

defined('ROOT') or header('Location: /index.php'); // Проверка на корректность вызова исполняемого файла

require_once(ROOT.'/system/class.php'); // Подключаем классы

//set_error_handler('reg_error'); // Устанавливаем свой обработчик ошибок

session_start(); // Инициализация сессии

// Подключение настроек
$cfg_dir = scandir(ROOT.'/system/config');
for ($i = 2; $i < count($cfg_dir); $i++){
    if (explode('.', $cfg_dir[$i])[count($cfg_dir[$i])+1] == 'php'){
        require_once(ROOT.'/system/config/'.$cfg_dir[$i]);
    }
}