<?php

//require_once(ROOT.'system/config/db.conf.php'); // Подключаем конфиг БД
//require_once(ROOT.'system/config/path.conf.php'); // Подключаем конфиг class

header("HTTP/1.0 200 OK");

require_once(ROOT.'system/func.php'); // Подключаем вспомогательные функции

// Подключение настроек
$cfg_dir = scandir(ROOT.'/system/config');
for ($i = 2; $i < count($cfg_dir); $i++){
    if (explode('.', $cfg_dir[$i])[count($cfg_dir[$i])+1] == 'php'){
        require_once(ROOT.'/system/config/'.$cfg_dir[$i]);
    }
}

//require_once DB; // Подключение базы данных
//require_once MODEL;// Подключение Модели
//require_once USER;// Подключение юзеров

// Подключение классов
$class_dir = scandir(ROOT.'/system/class');
for ($i = 2; $i < count($class_dir); $i++){
    if (explode('.', $class_dir[$i])[count($class_dir[$i])] == 'php'){
        require_once(ROOT.'/system/class/'.$class_dir[$i]);
    }
}

User::init(); // Запуск подсистемы пользователей и проверка статуса авторизации

if(!defined('AJAX')) {

    require_once LANG;
    $Model = new Model();

    //$User = new User();

    /*if($_POST){ // Это должно быть в модели reg, если что... А еще лучше мы это перенесем в Ajax
        if($_POST["reg_submit"]) $User->registration($_POST);
        exit;
    }*/
    $cur_page=false;
    //$cur_page = $_GET['page']; // site.com/index.php?page=reg  Умничка :)
    $link = explode('/', $_SERVER['REQUEST_URI']);
    if (!empty($link[1])) {
        $cur_page = strtolower($link[1]); // site.com/reg <= Так ведь наверняка хуже было
    } // И если ты не заметил, то все это было раньше в классе mvc.php

    if (!defined('USER_ID')) { // Когда мы не авторизованы
        /*switch(strtolower($cur_page)){
            case "auth":
                $page = "auth";
                $GLOBALS['title_page'] = "Авторизация";
                break;
            case "reg":
                $page = "reg";
                $GLOBALS['title_page'] = "Регистрация";
                break;
            default:
                $GLOBALS['title_page'] == "";
                $page = "main"; // зачем то отображаем странцу с интерфейсом ТМ
        }*/
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
    } else {
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

    require_once TMPL.'index.php';

    //define('TIME_END', microtime());
    //$time = TIME_END - TIME_START;
    //echo $time*1000 . 'мс';

}




