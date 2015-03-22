<?php

function head(){ // Фунция генерации основных элементов тега HEAD
    //$title = $GLOBALS['title_page'] == "" ? "EasyTM" : $GLOBALS['title_page'] . ' | EasyTM'; // Устанавливаем заголовок
    $title='EasyTM';
    $scripts='';
    if (defined('USER_ID')) { // Если пользователь авторизован
        $scripts.='<script>';
        $scripts.='SERVER=[];';
        $scripts.='SERVER["ID"]='.USER_ID.';';
        $scripts.='SERVER["NAME"]="'.USER_NAME.'";';
        $scripts.='SERVER["PIC"]="'.USER_PIC.'";';
        $scripts.='</script>';
    }

    // Подключение скриптов
    $js_dir = scandir(ROOT.'/js');
    for ($i = 2; $i < count($js_dir); $i++){
        if (explode('.', $js_dir[$i])[count($js_dir[$i])] == 'js'){
            $scripts.='<script src="/js/'.$js_dir[$i].'"></script>';
        }
    }
    return '<title>' . $title . '</title><meta charset="utf-8" />'.$scripts;
}

function Checkdata($string,$cls_probels=false){ // Функция обработки принятых данных
    // $cls_probels устанавливает разрешение на очистку строки от пробелов
    $string=stripslashes($string);
    $string=htmlspecialchars($string);
    $string=$cls_probels==true?trim($string):$string;
    return $string;
}

function genHash(){ // Фунуция генерации случайного хэша
    $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
    $numChars = strlen($chars);
    $string = '';
    $length=rand(8,16);
    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return md5($string);
}

function objectToArray($d){
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}