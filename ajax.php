<?php

define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/'); // Установка корневой директории
define('AJAX','CORE'); // Блокируем маршрутизацию

require_once ROOT.'system/core.php'; // Инициация запуска системы

$QUERY=@json_decode($_GET['query']); // Запрос
$QUERY=objectToArray($QUERY); // Трансформируем из Object в Array
$RESPONSE=array();

if(defined('USER_ID')) { // Статус авторизации
    $RESPONSE['auth'] = true;
    if(@$QUERY['action']=='logout') { // Выход из системы
        $out = User::logout();
        if ($out == true) {
            $RESPONSE['auth'] = false;
        }
    }
    if(@$QUERY['check']=='all') {
        $st = TM::show_task($QUERY);
        $RESPONSE['tasks'] = $st;
    }
    if(@$QUERY['action']=='add_task') { // Создание задачи
        $addt = TM::add_task($QUERY);
        $RESPONSE['add_task'] = $addt;
    }
    if(@$QUERY['action']=='set_task'){ // Изменение свойств задачи
        $setask=TM::set_task($QUERY);
        $RESPONSE['set_task']=$setask;
    }
    if(@$QUERY['action']=='get_comments'){
        $comm=TM::get_comm($QUERY);
        $RESPONSE['comments']=$comm;
    }
    if(@$QUERY['action']=='add_comment'){
        $RESPONSE['add_comment']=TM::add_comm($QUERY);
        $RESPONSE['comments']=TM::get_comm($QUERY);
    }
    if(@$QUERY['action']=='get_users'){
        $RESPONSE['users']=User::get_users($QUERY);
    }
    if(@$QUERY['action']=='show_projects'){
        $RESPONSE['projects']=TM::show_projects($QUERY);
    }
    if(@$QUERY['action']=='add_project'){
        $RESPONSE['add_project']=TM::add_project($QUERY);
    }
    if(@$QUERY['action']=='get_user') {
        $RESPONSE['get_user'] = User::get_user($QUERY);
    }

}else {
    $RESPONSE['auth'] = false;
    if (@$QUERY['action'] == 'reg') { // Регистрация
        $reg = User::registration($QUERY);
        $RESPONSE['reg'] = $reg;
    }
    if (@$QUERY['action'] == 'auth') { // Авторизация
        $auth = User::auth($QUERY['email'], $QUERY['pass']);
        $RESPONSE['auth'] = $auth;
    }
    if(@$QUERY['action']=='get_user') {
        $RESPONSE['get_user'] = User::get_user($QUERY,true);
    }
}

echo(json_encode($RESPONSE)); // Ответ