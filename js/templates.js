PAGE=[];
PART=[];

PART['header']='<header><ul class="menu"><li><a href="javascript:void(0)" onclick="page(\'tasks\');">Главная</a></li><li>' +
'<a href="javascript:void(0)" onclick="page(\'tasks\');">Задачи</a></li><li><a href="javascript:void(0)" onclick="page(\'projects\');">Проекты</a></li><li>' +
'<a href="javascript:void(0)" onclick="page(\'groups\');">Группы</a></li></ul><div class="user_menu">' +
'<div class="avatar"><img id="user_pic" src="/templates/default/images/avatar.png"></div><div><span id="user_name">USER_NAME</span></div>' +
'<div class="arrow"></div><ul><li><a>Мои данные</a></li><li><a>Настройки</a></li><li onclick="logout();"><a>Выход</a>' +
'</li></ul></div></header><div class="arrow-top"></div><div class="calendar"><div id="calendar"></div></div>';

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

PAGE['projects']=[];
PAGE['projects']['title']='Проекты';
PAGE['projects']['source']='<div class="left_bar"><div class="action_bar"><a href="javascript:void(0)" onclick="show_add_project();">' +
'<div class="add_task"><div class="plus"><div id="p1"></div><div id="p2"></div><div id="p3"></div>' +
'<div id="p4"></div></div>Создать проект</div></a>' +
'<div class="select"></div><div style="clear: both"></div></div><div id="projects"></div></div>' +
'<div class="center"><div class="project" id="view"></div></div>';

PAGE['reg']=[];
PAGE['reg']['title']='Регистрация';
PAGE['reg']['source']='<div class="regbody wrapperautreg" id="wrapper"><div class="formreg">' +
'<span class="Au1">Регистрация</span><form name="reg"><div class="auth">' +
'<input id="lastname" class="Au2" type="text" name="lastname" placeholder="Фамилия"></div><div class="auth">' +
'<input class="Au2" id="firstname" type="text" name="firstname" placeholder="Имя"></div><div class="auth">' +
'<input class="Au2" id="patronymic" type="text" name="patronymic" placeholder="Отчество"></div><div class="auth">' +
'<input class="Au2" id="password" type="password" name="password" placeholder="Пароль"></div><div class="auth">' +
'<input class="Au2" id="repeat_password" type="password" name="repeat_password" placeholder="Повторите пароль"></div>' +
'<div class="auth"><input class="Au2" id="email" type="email" name="email" placeholder="E-mail"></div><div class="auth">' +
'<input class="Au2" id="repeat_email" type="email" name="repeat_email" placeholder="Повторите e-mail"></div><div class="auth">' +
'<div onclick="reg_send()" class="authregbtn">Хочу зарегистрироваться</div></div>' +
'</form><div id="status"></div></div></div>';