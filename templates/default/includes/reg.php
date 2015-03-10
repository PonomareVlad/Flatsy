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
</head>
<body>
    <form name="reg">
        <label for="lastname">Фамилия: </label><input id="lastname" type="text" name="lastname" placeholder="Фамилия"><br>
        <label for="firstname">Имя: </label><input id="firstname" type="text" name="firstname" placeholder="Имя"><br>
        <label for="patronymic">Отчество: </label><input id="patronymic" type="text" name="patronymic" placeholder="Отчество"><br>
        <label for="password">Пароль: </label><input id="password" type="password" name="password" placeholder="Пароль"><br>
        <label for="email">E-mail: </label><input id="email" type="email" name="email" placeholder="E-mail"><br>
        <input type="button" name="reg_submit" onclick="regi();return false;" value="Хочу зарегистрироваться">
    </form>
<div id="status"></div>
</body>
</html>
