<?php

define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/'); // Установка корневой директории
define('AJAX','CORE'); // Блокируем маршрутизацию

require_once ROOT.'system/core.php'; // Инициация запуска системы

if(!isset($_GET['query'])){
    exit;
}

$QUERY=json_decode($_GET['query']); // Запрос
$QUERY=objectToArray($QUERY); // Трансформируем из Object в Array
$RESPONSE=array();

if(defined('USER_ID')) { // Статус авторизации
    $RESPONSE['auth'] = true;

    if (isset($QUERY['action'])) {
        if ($QUERY['action'] == 'check') {
            $NEW = [];
            $get = DB::select('notifications', ['*'], 'iduser=' . USER_ID);
            if ($get) {
                while ($notify = mysqli_fetch_assoc($get)) {
                    if ($notify['type'] == 'new_task') {
                        $task = TM::get_task($notify['value']);
                        if (!isset($NEW['TASK'])) {
                            $NEW['TASK'] = [];
                        }
                        $NEW['TASK'][] = $task;
                    }
                    if ($notify['type'] == 'new_comment') {
                        $comment = DB::select('comments', ['*'], 'id=' . $notify['value']);
                        $comment = mysqli_fetch_assoc($comment);
                        $comment['usercom_name'] = @implode(' ', mysqli_fetch_assoc(DB::select('users', ['firstname', 'lastname'], 'id=' . $comment['usercom'])));
                        $comment['usercom_photo'] = User::get_user($comment['usercom'])['photo'];
                        if (!isset($NEW['COMMENT'])) {
                            $NEW['COMMENT'] = [];
                        }
                        $NEW['COMMENT'][] = $comment;
                    }
                    DB::delete('notifications','idnotification='.$notify['idnotification']);
                }
                $RESPONSE['NEW'] = $NEW;
                $RESPONSE['check'] = true;
            } else {
                $RESPONSE['check'] = false;
            }
        }
        if ($QUERY['action']=='load_db'){
            $DB=[];
            $DB['TASK']=TM::get_tasks();
            $DB['PROJECT']=TM::get_projects();
            $DB['GROUP']=User::get_groups();
            $RESPONSE['DB']=$DB;
        }
        if ($QUERY['action'] == 'logout') { // Выход из системы
            $out = User::logout();
            if ($out == true) {
                $RESPONSE['auth'] = false;
            }
        }
        if ($QUERY['action'] == 'add_task') { // Создание задачи
            $addt = TM::add_task($QUERY);
            $RESPONSE['add_task'] = $addt;
        }
        if ($QUERY['action'] == 'set_task') { // Изменение свойств задачи
            $setask = TM::set_task($QUERY);
            $RESPONSE['set_task'] = $setask;
        }
        if ($QUERY['action'] == 'del_task') { // Удаление задачи
            $RESPONSE['del_task'] = TM::del_task($QUERY['id']);
        }
        if ($QUERY['action'] == 'edit_task') { // Изменение задачи
            $RESPONSE['edit_task'] = TM::edit_task($QUERY);
        }
        if ($QUERY['action'] == 'del_project') { // Удаление проекта
            $RESPONSE['del_project'] = TM::del_project($QUERY['id']);
        }
        if ($QUERY['action'] == 'del_user') { // Удаление проекта
            $RESPONSE['del_user'] = TM::del_user($QUERY['id'],$QUERY['group']);
        }
        if ($QUERY['action'] == 'edit_project') { // Изменение проекта
            $RESPONSE['edit_project'] = TM::edit_project($QUERY);
        }
        if ($QUERY['action'] == 'get_comments') {
            $comm = TM::get_comments($QUERY['id'],$QUERY['type']);
            $RESPONSE['comments'] = $comm;
        }
        if ($QUERY['action'] == 'add_comment') {
            $RESPONSE['new_comment'] = TM::add_comment($QUERY['id'],$QUERY['type'],$QUERY['text']);
            //$RESPONSE['comments'] = TM::get_comm($QUERY);
        }
        if ($QUERY['action'] == 'get_users') {
            $RESPONSE['users'] = User::get_users($QUERY);
        }
        if ($QUERY['action'] == 'show_projects') {
            $RESPONSE['projects'] = TM::show_projects($QUERY);
        }
        if ($QUERY['action'] == 'add_project') {
            $RESPONSE['add_project'] = TM::add_project($QUERY);
        }
        if ($QUERY['action'] == 'add_group') {
            $RESPONSE['add_group'] = TM::add_group($QUERY['name']);
        }
        if ($QUERY['action'] == 'get_user') {
            $RESPONSE['get_user'] = User::get_user($QUERY);
        }
    }
}else {
    $RESPONSE['auth'] = false;

    if (isset($QUERY['action'])) {
        if($QUERY['action']=='check'){
            $RESPONSE['check'] = false;
        }
        if ($QUERY['action'] == 'reg') { // Регистрация
            $reg = User::registration($QUERY);
            $RESPONSE['reg'] = $reg;
        }
        if ($QUERY['action'] == 'auth') { // Авторизация
            $auth = User::auth($QUERY['email'], $QUERY['pass']);
            $RESPONSE['auth'] = $auth;
        }
        if ($QUERY['action'] == 'get_user') {
            $RESPONSE['get_user'] = User::get_user($QUERY['email'], true);
        }
    }
}

echo(json_encode($RESPONSE)); // Ответ