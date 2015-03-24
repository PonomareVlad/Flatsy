PAGE=[];
PART=[];

PART['header']='<header><ul class="menu"><li><a href="#" onclick="page(\'tasks\');">Главная</a></li><li>' +
'<a href="#" onclick="page(\'tasks\');">Задачи</a></li><li><a href="#" onclick="page(\'projects\');">Проекты</a></li><li>' +
'<a href="#" onclick="page(\'groups\');">Группы</a></li></ul><div class="user_menu">' +
'<div class="avatar"><img id="user_pic" src="/templates/default/images/avatar.png"></div><div><span id="user_name">USER_NAME</span></div>' +
'<div class="arrow"></div><ul><li><a>Мои данные</a></li><li><a>Настройки</a></li><li><a onclick="logout();">Выход</a>' +
'</li></ul></div></header><div class="arrow-top"></div><div class="calendar"><div id="calendar"></div></div>';

PAGE['tasks']=[];
PAGE['tasks']['title']='Задачи';
PAGE['tasks']['source']='<div class="left_bar"><div class="action_bar"><a href="#" onclick="show_add_task();">' +
'<div class="add_task"><div class="plus"><div id="p1"></div><div id="p2"></div><div id="p3"></div><div id="p4"></div>' +
'</div>Добавить задачу</div></a><div class="selectul"><ul><div class="arrow"></div><li id="currentv"></li>' +
'<li id="none"><ul><li id="my" onclick="set_vmode(\'my\');">Мои задачи</li>' +
'<li id="unfinished" onclick="set_vmode(\'unfinished\');">Невыполненные задачи</li>' +
'<li id="all" onclick="set_vmode(\'all\');">Все задачи</li></ul></li></ul></div><div style="clear: both"></div></div>' +
'<div id="tasks"></div></div><div class="center"><div class="task" id="view"></div></div>';

PAGE['auth']=[];
PAGE['auth']['title']='Авторизация';
PAGE['auth']['source']='<div class="authbody wrapperautreg" id="wrapper"><div class="formauth"><span class="Au1">Авторизация</span>' +
'<form name="auth"><div class="auth" id="E"><div id="pic" style="height: 41px"></div>' +
'<input type="login" id="email" name="email" class="Au2" onfocus="TM[\'apic_loaded\']=false;document.getElementById(\'pic\').innerHTML=\'\';" autofocus placeholder="E-mail">' +
'</div><div class="auth">' +
'<input name="pass" id="pass" type="password" class="Au2" onfocus="load_enter_pic();" placeholder="Пароль">' +
'</div><div class="auth" id="In">' +
'<a href="#" onclick=\'auth_send();\' class="button box-shadow-outset">Вход</a></div></form>' +
'<a href="#" onclick="page(\'reg\');">Регистрация</a><br><a href="#" onclick="page(\'recovery\');>Восстановить пароль</a></div></div>';