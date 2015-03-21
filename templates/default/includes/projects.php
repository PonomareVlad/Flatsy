
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