<?php

class MVC
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
            MVC::ErrorPage404();
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