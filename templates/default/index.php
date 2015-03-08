<!DOCTYPE html>
<html>
<head>
    <?head()?>
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
                        <a>Выход</a>
                    </li>
                </ul>
            </div>
        </header>
        <div class="arrow-top"></div>
        <div class="calendar">
            <ul>
                <?for($i=8;$i<28;$i++):?>
                <li>
                    <span class="month">Ноя</span><br>
                    <span class="day"><?if($i<10) echo "0"; echo $i++;?></span><br>
                    <span class="week_day">пн</span>
                </li>
                <li>
                    <span class="month">Ноя</span><br>
                    <span class="day"><?if($i<10) echo "0"; echo $i++;?></span><br>
                    <span class="week_day">вт</span>
                </li>
                <li>
                    <span class="month">Ноя</span><br>
                    <span class="day"><?if($i<10) echo "0"; echo $i++;?></span><br>
                    <span class="week_day">ср</span>
                </li>
                <li>
                    <span class="month">Ноя</span><br>
                    <span class="day"><?if($i<10) echo "0"; echo $i++;?></span><br>
                    <span class="week_day">чт</span>
                </li>
                <li>
                    <span class="month">Ноя</span><br>
                    <span class="day"><?if($i<10) echo "0"; echo $i++;?></span><br>
                    <span class="week_day">пт</span>
                </li>
                <li>
                    <span class="month">Ноя</span><br>
                    <span class="day"><?if($i<10) echo "0"; echo $i++;?></span><br>
                    <span class="week_day">сб</span>
                </li>
                <li>
                    <span class="month">Ноя</span><br>
                    <span class="day"><?if($i<10) echo "0"; echo $i++;?></span><br>
                    <span class="week_day">вс</span>
                </li>
                <?endfor;?>
            </ul>
        </div>
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
                    <select>
                        <option value="all">Мои задачи</option>
                        <option value="all">Невыполненные задачи</option>
                        <option value="all">Все задачи</option>
                    </select>
                </div>
                <div style="clear: both"></div>
            </div>
            <div>
                <div class="task_day">
                    <div class="task_name">Сегодня</div>
                    <div class="task_info">Постановки задачи №1</div>
                    <div class="task_info">Постановки задачи №2</div>
                    <div class="task_info">Постановки задачи №3</div>
                </div>
                <div class="task_day active_day">
                    <div class="task_name">Вчера</div>
                </div>
            </div>
        </div>
        <div class="center">
            <h3>Постановка задачи №3</h3>
            <div class="text">Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание Описание </div>
            <div class="info">
                <table>
                    <tr>
                        <td>Проект</td>
                        <td>Проект №1</td>
                    </tr>
                    <tr>
                        <td>Инициатор</td>
                        <td>Иванов Иван</td>
                    </tr>
                    <tr>
                        <td>Проект</td>
                        <td>Иванов Иван, Иванов Иван, Иванов Иван, Иванов Иван, Иванов Иван, Иванов Иван</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>






















