<?php defined("ROOT") or header('Location: /');?>
<!doctype html>
<html>
<head>
    <?=head();?>
    <script>
        function regi(response) {
            if (response) {
                response = JSON.parse(response);
                if (response['reg']) {
                    if (response['reg'] == true) {
                        document.getElementById('status').innerHTML = 'Вы успешно зарегистрированы!';
                        window.location = '/auth';
                    }else if(response['reg']=='Login exists') {
                        document.getElementById('status').innerHTML = 'Почтовый адрес уже зарегистрирован';
                    }else if(response['reg']=='Bad data'){
                        document.getElementById('status').innerHTML = 'Вы ввели некорректные данные';
                    }else if(response['reg']=='Empty data'){
                        document.getElementById('status').innerHTML = 'Необходимо заполнить все поля';
                    }else{
                        document.getElementById('status').innerHTML = 'Ошибка БД: ' +
                        ''+response['reg'];
                    }
                }
            } else {
                send = {
                    "action": "reg",
                    "lastname": document.getElementById('lastname').value,
                    "firstname": document.getElementById('firstname').value,
                    "patronymic": document.getElementById('patronymic').value,
                    "password": document.getElementById('password').value,
                    "email": document.getElementById('email').value
                };
                query = JSON.stringify(send);
                Ajax('GET', '/ajax.php?query=' + query + '&rand=' + new Date().getTime(), 'regi');
            }
        };
    </script>
    <link href="/<?=DIR_TMPL?>styles/style.css" rel="stylesheet" type="text/css" />
</head>

<body onload="init();" class="wrapperautreg">
<div class="wrapper">
    <div class="regbody">
        <div class="formreg">
            <span class="Au1">Регистрация</span>
            <form name="reg">
                <div class="auth">
                    <input id="lastname" class="Au2" type="text" name="lastname" placeholder="Фамилия">
                </div>
                <div class="auth">
                    <input class="Au2" id="firstname" type="text" name="firstname" placeholder="Имя">
                </div>
                <div class="auth">
                    <input class="Au2" id="patronymic" type="text" name="patronymic" placeholder="Отчество">
                </div>
                <div class="auth">
                    <input class="Au2" id="password" type="password" name="password" placeholder="<?=$LANG['PASSWORD']?>">
                </div>
                <div class="auth">
                    <input class="Au2" id="repeat_password" type="password" name="repeat_password" placeholder="<?=$LANG['REPEAT_PASSWORD']?>">
                </div>
                <div class="auth">
                    <input class="Au2" id="email" type="email" name="email" placeholder="<?=$LANG['EMAIL']?>">
                </div>
                <div class="auth">
                    <input class="Au2" id="repeat_email" type="email" name="repeat_email" placeholder="<?=$LANG['REPEAT_EMAIL']?>">
                </div>
                <div class="auth">
                    <div onclick="regi();" class="authregbtn">Хочу зарегистрироваться</div>
                </div>
            </form>
            <div id="status"></div>
        </div>
    </div>
</div>
</body>
</html>