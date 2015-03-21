<?php defined("ROOT") or header('Location: /');?>
<!DOCTYPE html>
<html>
<head>
    <?=head()?>
    <link href="<?=DIR_TMPL?>styles/style.css" rel="stylesheet" type="text/css" />
</head>
<body onload="init();" onresize="sizing();">
<div class="wrapper">
    <header>
        <ul class="menu">
            <li>
                <a href="/">Главная</a>
            </li>
            <li>
                <a href="/main">Задачи</a>
            </li>
            <li>
                <a href="/projects">Проекты</a>
            </li>
            <li>
                <a href="/group">Группы</a>
            </li>
        </ul>
        <div class="user_menu">
            <div class="avatar"><img src="<?=DIR_TMPL;?>images/avatar.png"></div>
            <div><span id="username"><?=USER_NAME?></span></div>
            <div class="arrow"></div>
            <ul>
                <li>
                    <a>Мои данные</a>
                </li>
                <li>
                    <a>Настройки</a>
                </li>
                <li>
                    <a onclick="logout();">Выход</a>
                </li>
            </ul>
        </div>
    </header>
    <div class="arrow-top"></div>
    <div class="calendar">
        <div id="calendar"></div>
    </div>