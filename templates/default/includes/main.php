<div class="left_bar">
        <div class="action_bar">
            <a href="#" onclick="show_add_task();">
            <div class="add_task">
                <div class="plus">
                    <div id="p1"></div>
                    <div id="p2"></div>
                    <div id="p3"></div>
                    <div id="p4"></div>
                </div>
                Добавить задачу
            </div>
            </a>
            <div class="selectul">
                <ul>
                    <div class="arrow"></div>
                    <li id="currentv"></li>
                    <li id="none">
                        <ul>
                            <li id="my" onclick="set_vmode('my');">Мои задачи</li>
                            <li id="unfinished" onclick="set_vmode('unfinished');">Невыполненные задачи</li>
                            <li id="all" onclick="set_vmode('all');">Все задачи</li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div style="clear: both"></div>
        </div>
        <div id="tasks"></div>
    </div>
    <div class="center task" id="view">
        <div>Выберите задачу для просмотра</div>
    </div>