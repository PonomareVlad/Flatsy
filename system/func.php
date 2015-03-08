<?php

function head(){
    $title = $GLOBALS['title_page'] == "" ? "EasyTM" : $GLOBALS['title_page'] .' | EasyTM';
    echo '<title>'.$title.'</title><meta charset="utf-8" /><script src="/js/main.js"></script>';
}