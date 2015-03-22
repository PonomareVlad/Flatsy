<!DOCTYPE html>
<html>
<head>
    <?=head()?>
    <script>
        pic_loaded=false;
        document.onkeyup = function (e) {
            e = e || window.event;
            if (e.keyCode === 13) {
                if(document.getElementById('passi').value!='') {
                    authi();
                }
            }
            return false;
        }
        function load_enter_pic(response){
            document.getElementById('passi').placeholder='Пароль';
            if(response){
                response = JSON.parse(response);
                //alert(response['get_user']);
                if(response['get_user']!=false){
                    document.getElementById('pic').innerHTML='<div class="avatar"><img src="'+response['get_user']['photo']+'"></div>';
                }
            }else {
                if (pic_loaded == false) {
                    pic_loaded=true;
                    io({"action": "get_user", "email": document.getElementById('emaili').value}, 'load_enter_pic');
                }
            }
        }
        function authi(response) {
            if (response) {
                response = JSON.parse(response);
                if (response['auth'] == false) {
                    document.getElementById('passi').value='';
                    //alert('Incorrect');
                    document.getElementById('passi').placeholder='Неверный пароль!';
                } else {
                    window.location = '/main';
                }
            }else{
                send = {
                    "action": "auth",
                    "email": document.getElementById('emaili').value,
                    "pass": document.getElementById('passi').value
                };
                document.getElementById('passi').value='';
                query = JSON.stringify(send);
                Ajax('GET', '/ajax.php?query=' + query + '&rand=' + new Date().getTime(), 'authi');
            }
        };
    </script>
    <link href="/<?=DIR_TMPL?>styles/style.css" rel="stylesheet" type="text/css" />
</head>
<body onload="init();" class="wrapperautreg">
    <div class="wrapper">
        <div class="authbody">
            <div class="formauth">
                <span class="Au1">Авторизация</span>
                <form name="auth">
                    <div class="auth" id="E"><div id="pic" style="height: 41px"></div>
                        <input type="login" id="emaili" name="email" class="Au2" onfocus="pic_loaded=false;document.getElementById('pic').innerHTML='';" autofocus placeholder="E-mail">
                    </div>
                    <div class="auth">
                        <input name="pass" id="passi" type="password" class="Au2" onfocus="load_enter_pic();" placeholder="<?=$LANG['PASSWORD']?>">
                    </div>
                    <div id="In">
                        <a href="#" onclick='authi();' class="button box-shadow-outset">
                            <div class="authregbtn"><?=$LANG['AUTH_ENTER_TITLE']?></div>
                        </a>
                    </div>
                    <a href="reg">
                        <div class="authregbtn" ><?=$LANG['REG_TITLE']?></div>
                    </a>
                </form>

                <a href="recovery.php" ><?=$LANG['RECOVERY_PASS']?></a>
            </div>
        </div>
    </div>
</body>
</html>