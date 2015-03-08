<!DOCTYPE html>
<html>
<head>
    <?head()?>
    <link href="<?=DIR_TMPL?>styles/auth.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="wrapper">
        <div class="authbody">
            <div class="formauth">
                <span class="Au1">Авторизация</span>
                <form name="auth" method="get" action="system/auth.php">
                    <div class="auth" id="E">
                        <input type="login" id="email" name="email" class="Au2" required placeholder="  E-mail">
                    </div>
                    <div class="auth">
                        <input name="pass" id="pass" type="password" class="Au2" required placeholder="  '.$LANG['PASSWORD'].'">
                    </div>
                    <div class="auth" id="In">
                        <a href="javascript: document.forms[\'auth\'].submit()" class="button box-shadow-outset">'.$LANG['AUTH_ENTER_TITLE'].'</a>
                    </div>
                </form>
                <a href="reg.php" class="button box-shadow-outset">'.$LANG['REG_TITLE'].'</a>
                <div class="auth">
                    <a href="recovery.php" class="Au3">'.$LANG['RECOVERY_PASS'].'</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>