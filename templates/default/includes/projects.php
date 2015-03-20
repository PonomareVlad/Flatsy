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
            <div><span id="username"><?=USER_NAME?></span></div>
            <div id="arrow"></div>
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
    <div class="calendar" id="calendar"></div>
    <div class="left_bar">
        <div class="action_bar">
            <a href="#" onclick="show_add_project();">
            <div class="add_task">
                <div class="plus">
                    <div id="p1"></div>
                    <div id="p2"></div>
                    <div id="p3"></div>
                    <div id="p4"></div>
                </div>
                Создать проект
            </div>
            </a>
            <div class="select">
                <select id="view_mode" onchange="projects_mode=this.value;check('all');">
                    <option value="my">Мои проекты</option>
                    <option value="unfinished">Невыполненные проекты</option>
                    <option value="all" selected="selected">Все проекты</option>
                </select>
            </div>
            <div style="clear: both"></div>
        </div>
        <div id="projects"></div>
    </div>
    <div class="center task" id="view">
        <div>Выберите проект для просмотра</div>
    </div>
</div>
</body>
</html>