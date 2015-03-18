<?php defined("ROOT") or header('Location: /');?>
<!DOCTYPE html>
<html>
<head>
    <?=head()?>
    <link href="<?=DIR_TMPL?>styles/style.css" rel="stylesheet" type="text/css" />
</head>
<body onload="init();">
<div class="wrapper">
    <header>
        <ul class="menu">
            <li>
                <a href="/">Главная</a>
            </li>
            <li>
                <a href="index.php">Задачи</a>
            </li>
            <li>
                <a href="index.php">Проекты</a>
            </li>
            <li>
                <a href="index.php">Группы</a>
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
            <div class="add_task">
                <div class="plus">
                    <div id="p1"></div>
                    <div id="p2"></div>
                    <div id="p3"></div>
                    <div id="p4"></div>
                </div>
                Добавить задание
            </div>
            <div class="select">
                <select id="view_mode" onchange="tasks_mode=this.value;check('all');">
                    <option value="my">Мои задачи</option>
                    <option value="unfinished">Невыполненные задачи</option>
                    <option value="all" selected="selected">Все задачи</option>
                </select>
            </div>
            <div style="clear: both"></div>
        </div>
        <div id="tasks"></div>
    </div>
    <div class="center" id="view">
        <div>Выберите задачу для просмотра</div>
    </div>

</div>
</body>
</html>