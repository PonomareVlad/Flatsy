<?php

class Route
{
    static function start()
    {
        // контроллер и действие по умолчанию
        //$controller_name = 'Main';
        $page_name = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']);

        // получаем имя контроллера
        if ( !empty($routes[1]) )
        {
            $page_name = $routes[1];
        }

        // получаем имя экшена
        //if ( !empty($routes[2]) )
        //{
        //    $action_name = $routes[2];
        //}

        // добавляем префиксы
        //$model_name = 'Model_'.$controller_name;
        //$controller_name = 'Controller_'.$controller_name;
        //$action_name = 'action_'.$action_name;

        // подцепляем файл с классом модели (файла модели может и не быть)

        $page_file = strtolower($page_name);
        $page_path = TMPL.$page_file;
        if(file_exists($page_path))
        {
            define('PAGE_PATH',TMPL.$page_file);
            require_once(ROOT.'system/model/'.$page_file);
        }

        // подцепляем файл с классом контроллера
        //$controller_file = strtolower($controller_name).'.php';
        //$controller_path = "application/controllers/".$controller_file;
        //if(file_exists($controller_path))
        //{
        //    include "application/controllers/".$controller_file;
        //}
        else
        {
            /*
            правильно было бы кинуть здесь исключение,
            но для упрощения сразу сделаем редирект на страницу 404
            */
            Route::ErrorPage404();
        }

        // создаем контроллер
        //$controller = new $controller_name;
        //$action = $action_name;

        //if(method_exists($controller, $action))
        //{
            // вызываем действие контроллера
            //$controller->$action();
        //}
        //else
        //{
            // здесь также разумнее было бы кинуть исключение
        //    Route::ErrorPage404();
        //}

    }

    static function view(){

        global $head;
        require_once PAGE_PATH;

    }

    function ErrorPage404()
    {
        $host = '/index.php';
        //header('HTTP/1.1 404 Not Found');
        //header("Status: 404 Not Found");
        header('Location:'.$host);
    }
}

class DB {

    public function __construct(){
        define('MYSQL_CONNECTION',mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASSWORD)); // Подключение к БД
        mysql_select_db('tm'); // Выбор БД
    }

    protected function select($table_name, $fields, $where = "", $order = "", $up = true, $limit = ""){
        for ($i = 0; $i < count ($fields); $i++){
            if ((strpos($fields[$i], "(") === false) && ($fields[$i] != "*")) $fields[$i] = "`".$fields[$i]."`";
        }
        $fields = implode(",", $fields);
        if (!$order) $order = "";
        else {
            if ($order != "RAND()"){
                $order = "ORDER BY `$order`";
                if (!$up) $order .= " DESC";
            }
            else $order = "ORDER BY $order";
        }
        if ($limit) $limit = "LIMIT $limit";
        if ($where) $query = "SELECT $fields FROM $table_name WHERE $where $order $limit";
        else $query = "SELECT $fields FROM $table_name $order $limit";
        $res = mysql_query($query) or die(mysql_error());
        return $res;
    }

    protected function update ($table_name, $upd_fields, $where){
        $query = "UPDATE $table_name SET ";
        foreach ($upd_fields as $field => $value) $query .= "`$field` = '".addslashes($value)."',";
        $query = substr($query, 0, -1);
        if ($where){
            $query .= " WHERE $where";
            $res = mysql_query($query) or die(mysql_error());
            return $res;
        }
        else return false;
    }

    protected function insert ($table_name, $new_value){
        $table_name = $this->config->db_prefix.$table_name;
        $query = "INSERT INTO $table_name (";
        foreach ($new_value as $field => $value) $query .= "`".$field."`,";
        $query = substr($query, 0, -1);
        $query .= ") VALUES (";
        foreach ($new_value as $value) $query .= "'".addslashes($value)."',";
        $query = substr($query, 0, -1);
        $query .= ")";
        $res = mysql_query($query) or die(mysql_error());
        return $res;
    }



    protected function delete ($table_name, $where = ""){
        if ($where){
            $query = "DELETE FROM $table_name WHERE $where";
            $res = mysql_query($query) or die(mysql_error());
            return $res;
        }
        else return false;
    }
}

function reg_error($errno, $errstr, $errfile, $errline){ // Обработчик ошибок для вывода их в системный лог
    $LOG='[ERROR LOG START HERE]';
    switch ($errno) {
        case E_USER_ERROR:
            $LOG.= "<b>My ERROR</b> [$errno] $errstr<br />\n";
            $LOG.= "  Фатальная ошибка в строке $errline файла $errfile";
            $LOG.= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            $LOG.= "Завершение работы...<br />\n";
            exit(1);
            break;

        case E_USER_WARNING:
            $LOG.= "<b>My WARNING</b> [$errno] $errstr<br />\n";
            break;

        case E_USER_NOTICE:
            $LOG.= "<b>My NOTICE</b> [$errno] $errstr<br />\n";
            break;

        default:
            $LOG.= "Неизвестная ошибка: [$errno] $errstr<br />\n";
            break;
    }
    $LOG.='[ERROR LOG END HERE]';
    return true;
}