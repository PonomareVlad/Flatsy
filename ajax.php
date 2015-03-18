<?php

define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/'); // Установка корневой директории
define('AJAX','CORE'); // Блокируем маршрутизацию

require_once ROOT.'system/core.php'; // Инициация запуска системы

$QUERY=@json_decode($_GET['query']); // Запрос
$QUERY=objectToArray($QUERY); // Трансформируем из Object в Array
$RESPONSE=array();

if(defined('USER_ID')) { // Статус авторизации
    $RESPONSE['auth'] = true;
    if (@$QUERY['action'] == 'logout') { // Выход из системы
        $out = User::logout();
        if ($out == true) {
            $RESPONSE['auth'] = false;
        }
    }
    if (@$QUERY['action'] == 'add_task') { // Создание задачи
        $addt = TM::add_task($QUERY);
        $RESPONSE['add_task'] = $addt;
    }
    if(@$QUERY['action']=='set_task'){ // Изменение свойств задачи
        $setask=TM::set_task($QUERY);
        $RESPONSE['set_task']=$setask;
    }
    if (@$QUERY['check'] == 'all') {
        $st = TM::show_task($QUERY);
        $RESPONSE['tasks'] = $st;
    }
}else {
    $RESPONSE['auth'] = false;
    if ($QUERY['action'] == 'reg') { // Регистрация
        $reg = User::registration($QUERY);
        $RESPONSE['reg'] = $reg;
    }
    if ($QUERY['action'] == 'auth') { // Авторизация
        $auth = User::auth($QUERY['email'], $QUERY['pass']);
        $RESPONSE['auth'] = $auth;
    }

}

echo(json_encode($RESPONSE)); // Ответ