<?php

function head(){
    $title = $GLOBALS['title_page'] == "" ? "EasyTM" : $GLOBALS['title_page'] .' | EasyTM';
    if(defined('USER_ID')) {
        return '<title>' . $title . '</title><meta charset="utf-8" /><script>auth=true;</script><script src="/js/main.js"></script>';
    }else{
        return '<title>' . $title . '</title><meta charset="utf-8" /><script src="/js/main.js"></script>';
    }
}

function Checkdata($string){ // Функция обработки принятых данных

    $string=stripslashes($string);
    $string=htmlspecialchars($string);
    $string=trim($string);

    return $string;
}

function genHash($length = 8){
    $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
    $numChars = strlen($chars);
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return $string;
}

function objectToArray($d)
{
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