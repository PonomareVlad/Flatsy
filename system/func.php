<?php

function reg_error($errno, $errstr, $errfile, $errline)
{ // Обработчик ошибок для вывода их в системный лог
    $LOG = '[ERROR LOG START HERE]';
    switch ($errno) {
        case E_USER_ERROR:
            $LOG .= "<b>My ERROR</b> [$errno] $errstr<br />\n";
            $LOG .= "  Фатальная ошибка в строке $errline файла $errfile";
            $LOG .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            $LOG .= "Завершение работы...<br />\n";
            exit(1);
            break;

        case E_USER_WARNING:
            $LOG .= "<b>My WARNING</b> [$errno] $errstr<br />\n";
            break;

        case E_USER_NOTICE:
            $LOG .= "<b>My NOTICE</b> [$errno] $errstr<br />\n";
            break;

        default:
            $LOG .= "Неизвестная ошибка: [$errno] $errstr<br />\n";
            break;
    }
    $LOG .= '[ERROR LOG END HERE]';
    return true;
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