<!DOCTYPE html>
<html>
<head>
    <?=head()?>
    <script>
        function authi(response) {
            if (response) {
                response = JSON.parse(response);
                if (response['auth'] == false) {
                    alert('Incorrect');
                } else {
                    window.location = '/main';
                }
            }else{
                send = {
                    "action": "auth",
                    "email": document.getElementById('emaili').value,
                    "pass": document.getElementById('passi').value
                };
                query = JSON.stringify(send);
                Ajax('GET', '/ajax.php?query=' + query + '&rand=' + new Date().getTime(), 'authi');
            }
        };
    </script>
    <link href="<?=DIR_TMPL?>styles/auth.css" rel="stylesheet" type="text/css" />
</head>
<body onload="init();">
    <div class="wrapper">
        <div class="authbody">
            <div class="formauth">
                <span class="Au1">Авторизация</span>
                <form name="auth">
                    <div class="auth" id="E">
                        <input type="login" id="emaili" name="email" class="Au2" required placeholder="  E-mail">
                    </div>
                    <div class="auth">
                        <input name="pass" id="passi" type="password" class="Au2" required placeholder="  <?=$LANG['PASSWORD']?>">
                    </div>
                    <div class="auth" id="In">
                        <a href="#" onclick='authi();' class="button box-shadow-outset"><?=$LANG['AUTH_ENTER_TITLE']?></a>
                    </div>
                </form>
                <a href="reg" ><?=$LANG['REG_TITLE']?></a><br>
                <a href="recovery.php" ><?=$LANG['RECOVERY_PASS']?></a>
            </div>
        </div>
    </div>
</body>
</html>