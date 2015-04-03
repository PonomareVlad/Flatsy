PAGE=[];
PART=[];

PART['header']='<header><ul class="menu"><li><a href="javascript:void(0)" onclick="page(\'tasks\');">Главная</a></li><li>' +
'<a href="javascript:void(0)" onclick="page(\'tasks\');">Задачи</a></li><li><a href="javascript:void(0)" onclick="page(\'projects\');">Проекты</a></li><li>' +
'<a href="javascript:void(0)" onclick="page(\'groups\');">Группы</a></li></ul><div class="user_menu">' +
'<div class="avatar"><img id="user_pic" src="/templates/default/images/avatar.png"></div><div><span id="user_name">USER_NAME</span></div>' +
'<div class="arrow"></div><ul><li><a>Мои данные</a></li><li onclick="page(\'lk\',true);"><a>Настройки</a></li><li onclick="logout();"><a>Выход</a>' +
'</li></ul></div><div class="notifications"><div>Уведомления(2)</div><ul><li><a>Уведомлениие 1<img src="templates/default/images/cancel_hover.png"></a> ' +
'</li><li><a>Уведомлениие 2<img src="templates/default/images/cancel_hover.png"></a></li><li>' +
'<a>Уведомле 3<img src="templates/default/images/cancel_hover.png"></a></li></ul>' +
'</div></header><div class="arrow-top"></div><div class="calendar"><div id="prev"></div><div id="calendar"></div><div id="next"></div></div>';

PART['header_slim']='<header><ul class="menu"><li><a href="javascript:void(0)" onclick="page(\'tasks\',true);">Главная</a></li><li>' +
'<a href="javascript:void(0)" onclick="page(\'tasks\',true);">Задачи</a></li><li><a href="javascript:void(0)" onclick="page(\'projects\',true);">Проекты</a></li><li>' +
'<a href="javascript:void(0)" onclick="page(\'groups\',true);">Группы</a></li></ul><div class="user_menu">' +
'<div class="avatar"><img id="user_pic" src="/templates/default/images/avatar.png"></div><div><span id="user_name">USER_NAME</span></div>' +
'<div class="arrow"></div><ul><li><a>Мои данные</a></li><li onclick="page(\'lk\',true);"><a>Настройки</a></li><li onclick="logout();"><a>Выход</a>' +
'</li></ul></div><div class="notifications"><div>Уведомления(2)</div><ul><li><a>Уведомлениие 1<img src="templates/default/images/cancel_hover.png"></a> ' +
'</li><li><a>Уведомлениие 2<img src="templates/default/images/cancel_hover.png"></a></li><li>' +
'<a>Уведомле 3<img src="templates/default/images/cancel_hover.png"></a></li></ul>' +
'</div></header><div class="arrow-top"></div>';

PART['loader']='<div id="overlay" class="overlay"><div id="spinner" class="spinner center"><div class="spinner-blade"></div><div class="spinner-blade"></div><div class="spinner-blade"></div><div class="spinner-blade"></div><div class="spinner-blade"></div><div class="spinner-blade"></div><div class="spinner-blade"></div><div class="spinner-blade"></div><div class="spinner-blade"></div><div class="spinner-blade"></div><div class="spinner-blade"></div><div class="spinner-blade"></div></div></div>'

PAGE['tasks']=[];
PAGE['tasks']['title']='Задачи';
PAGE['tasks']['source']='<div class="left_bar"><div class="action_bar"><a href="javascript:void(0)" onclick="show_add_task();">' +
'<div class="add_task"><div class="plus"><div id="p1"></div><div id="p2"></div><div id="p3"></div><div id="p4"></div>' +
'</div>Добавить задачу</div></a><div class="selectul"><ul><div class="arrow"></div><li id="currentv"></li>' +
'<li id="none"><ul><li id="my" onclick="set_vmode(\'my\');">Мои задачи</li>' +
'<li id="unfinished" onclick="set_vmode(\'unfinished\');">Невыполненные задачи</li>' +
'<li id="all" onclick="set_vmode(\'all\');">Все задачи</li></ul></li></ul></div><div style="clear: both"></div></div>' +
'<div id="tasks"></div></div><div class="center"><div class="task" id="view"></div></div>';

PAGE['projects']=[];
PAGE['projects']['title']='Проекты';
PAGE['projects']['source']='<div class="left_bar"><div class="action_bar"><a href="javascript:void(0)" onclick="show_add_project();">' +
'<div class="add_task"><div class="plus"><div id="p1"></div><div id="p2"></div><div id="p3"></div>' +
'<div id="p4"></div></div>Создать проект</div></a>' +
'<div class="select"></div><div style="clear: both"></div></div><div id="projects"></div></div>' +
'<div class="center"><div class="task" id="view"></div></div>';

PAGE['groups']=[];
PAGE['groups']['title']='Группы';
PAGE['groups']['source']='<div class="left_bar groupflag"><div class="action_bar"><div onclick="show_add_group();" class="add_group">' +
'<div class="plus"><div id="p1"></div><div id="p2"></div><div id="p3"></div><div id="p4"></div></div>Добавить группу</div>' +
'<div onclick="groups_query();"><img src="templates/default/images/zaprosy.png"></div></div>' +
'<div class="group_list" id="groups"></div></div><div class="center group_center" id="view"></div>';

PAGE['auth']=[];
PAGE['auth']['title']='Авторизация';
PAGE['auth']['source']='<div style="display:none" id="load_pic"></div>' +
'<div class="authbody wrapperautreg" id="wrapper"><div class="formauth"><span class="Au1">Авторизация</span>' +
'<form name="auth"><div class="auth" id="E"><div id="pic" style="height: 41px"></div>' +
'<input type="login" id="email" name="email" class="Au2" onfocus="TM[\'apic_loaded\']=false;document.getElementById(\'pic\').innerHTML=\'\';" autofocus placeholder="E-mail">' +
'</div><div class="auth">' +
'<input name="pass" id="pass" type="password" class="Au2" onfocus="load_enter_pic();" placeholder="Пароль">' +
'</div><div class="auth" id="In">' +
'<a href="javascript:void(0)" onclick=\'auth_send();\' class="button box-shadow-outset"><div class="authregbtn">Вход</div></a></div></form>' +
'<a href="javascript:void(0)" onclick="page(\'reg\');"><div class="authregbtn" >Регистрация</div></a></div></div>';

PAGE['reg']=[];
PAGE['reg']['title']='Регистрация';
PAGE['reg']['source']='<div class="regbody wrapperautreg" id="wrapper"><div class="formreg">' +
'<span class="Au1">Регистрация</span><form name="reg"><div class="auth">' +
'<input class="Au2" id="email" type="email" name="email" placeholder="E-mail"></div><div class="auth">' +
'<input class="Au2" id="repeat_email" onkeyup="reg_check(\'email\')" type="email" name="repeat_email" placeholder="Повторите e-mail"></div><div class="auth">' +
'<input id="lastname" class="Au2" type="text" name="lastname" placeholder="Фамилия"></div><div class="auth">' +
'<input class="Au2" id="firstname" type="text" name="firstname" placeholder="Имя"></div><div class="auth">' +
'<input class="Au2" id="patronymic" type="text" name="patronymic" placeholder="Отчество"></div><div class="auth">' +
'<input class="Au2" id="password" type="password" name="password" placeholder="Пароль"></div><div class="auth">' +
'<input class="Au2" id="repeat_password" onkeyup="reg_check(\'password\')" type="password" name="repeat_password" placeholder="Повторите пароль"></div>' +
'<div class="auth"><input class="Au2" id="code" type="text" name="code" placeholder="Код"></div>' +
'<div class="auth"><div onclick="reg_send()" class="authregbtn">Хочу зарегистрироваться</div>' +
'<div onclick="page(\'auth\');" class="authregbtn">Авторизация</div></div>' +
'</form><div id="status"></div></div></div>';

PAGE['lk']=[];
PAGE['lk']['title']='Настройки';
PAGE['lk']['source']='<div id="lk"><div id="types"><div id="profile_settings" class="active_set">' +
'<img src="templates/default/images/set_id_active.png">Настройки профиля</div><div id="group_manage">' +
'<img src="templates/default/images/set_group.png">Управление группами</div>' +
'<div id="interface_set"><img src="templates/default/images/set_face.png">Настройки интерфейса</div></div>' +
'<div id="info_lk"><div id="photo_lk"><img src="templates/default/images/avatar_300.png" width="200"><br><a>Изменить фото</a></div>' +
'<div id="center_lk"><div id="user_info"><p><label for="last_name">Фамилия</label><input type="text" id="last_name" placeholder="Фамилия"></p>' +
'<p><label for="first_name">Имя</label><input type="text" id="first_name" placeholder="Имя"></p>' +
'<p><label for="otchestvo">Отчество</label><input type="text" id="otchestvo" placeholder="Отчество"></p>' +
'<p><label for="job_pos">Должность</label><input type="text" id="job_pos" placeholder="Должность"></p>' +
'<p><label for="group">Группа</label><input type="text" id="group" placeholder="Группа"></p>' +
'<p><label for="phone">Телефон</label><input type="text" id="phone" placeholder="Телефон"></p>' +
'<p><label for="E-mail">E-mail</label><input type="text" id="E-mail" placeholder="E-mail"></p>' +
'<p><label for="organization">Организация</label><input type="text" id="organization" placeholder="Организация"></p>' +
'<p><label for="organization_group">Подразделение</label><input type="text" id="organization_group" placeholder="Подразделение"></p>' +
'<div class="create">Сохранить</div></div><div id="pass_info">' +
'<p><label for="cur_pass">Текущий пароль</label><input type="text" id="cur_pass" placeholder="Текущий пароль"></p>' +
'<p><label for="new_pass">Новый пароль</label><input type="text" id="new_pass" placeholder="Новый пароль"></p>' +
'<p><label for="rep_pass">Повтор пароля</label><input type="text" id="rep_pass" placeholder="Повтор пароля"></p>' +
'<div class="create">Сохранить</div></div></div><div class="groups">' +
'<div>Название группы</div><div>Название группы</div><div>Название группы</div></div></div></div>';

