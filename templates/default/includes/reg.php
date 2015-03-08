<?php defined("ROOT") or header('Location: /');?>
<!doctype html>
<html>
<head>
    <?head();?>
</head>
<body>
    <form action="/" method="post" name="reg">
        <label for="lastname">Фамилия: </label><input id="lastname" type="text" name="lastname" placeholder="Фамилия"><br>
        <label for="firstname">Имя: </label><input id="firstname" type="text" name="firstname" placeholder="Имя"><br>
        <label for="patronymic">Отчество: </label><input id="patronymic" type="text" name="patronymic" placeholder="Отчество"><br>
        <label for="password">Пароль: </label><input id="password" type="password" name="password" placeholder="Пароль"><br>
        <label for="email">E-mail: </label><input id="email" type="email" name="email" placeholder="E-mail"><br>
        <input type="submit" name="reg_submit" value="Хочу зарегистрироваться">
    </form>
</body>
</html>
