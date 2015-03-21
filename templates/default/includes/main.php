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
                    <li id="my">Мои задачи </li>
                    <li id="none">
                        <ul>
                            <li id="unfinished">Невыполненные задачи</li>
                            <li id="all">Все задачи</li>
                        </ul>
                    </li>
                </ul>
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
    <div class="center task" id="view">
        <div>Выберите задачу для просмотра</div>
    </div>