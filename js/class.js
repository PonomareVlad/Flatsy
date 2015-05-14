function Ajax(method,url,callback){
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            TM['OFFLINE']=false;
            response=xmlhttp.responseText;
            //eval(callback+'(\''+response+'\');');
            try{var jsonObject = JSON.parse(response);}catch(e){
                // handle error
                return false;
            }
            callback(response);
        }else{
            TM['OFFLINE']=true;
            callback('{"offline":"true"}');
        }
    }
    xmlhttp.open(method,url,true);
    xmlhttp.send();
}

function init_cal(){
    get('calendar').innerHTML='<ul id="calend">'+gen_cal(-1)+gen_cal(0)+gen_cal(1)+'</ul>';
    if(!TM['calendar_scroll']){TM['calendar_scroll']=get('calendar_today').offsetLeft-((window.innerWidth-60)/2);}
    get('calendar').scrollLeft=TM['calendar_scroll'];
    get('calendar').onscroll=function(){TM['calendar_scroll']=get('calendar').scrollLeft};
}

function gen_cal(offset){
    // Calendar generation
    if(!offset){
        offset=0;
    }
    D1 = new Date();
    D1last = new Date(D1.getFullYear(),D1.getMonth()+offset+1,0).getDate(); // последний день месяца
    month=["Янв","Фев","Мар","Апр","Май","Июн","Июл","Авг","Сен","Окт","Ноя","Дек"]; // название месяца, вместо цифр 0-11
    days=['вс','пн','вт','ср','чт','пт','сб'];
    array=[];
    cal_view='';
    current_day=100;
    for(var  i = 1; i <= D1last; i++) {
        tmp=array.length;
        array[tmp]=[i,days[new Date(D1.getFullYear(),D1.getMonth()+offset,i).getDay()],month[D1.getMonth()+offset]];
        if (offset==0&&i == D1.getDate()) {
            current_day=tmp;
        }
    }
    for(i in array) {
        link=false;
        for(t in DB['TASK']) {
            task = DB['TASK'][t];
            if(task['finished'] == 0&&task['view'] == true){
                date_finish = task['date_finish'].split(' ');
                date_finish = date_finish[0].split('-');
                if(parseInt(date_finish[1])==D1.getMonth()+offset+1){
                    if(date_finish[2]==array[i][0]){
                        link=task['id'];//new Date(date_finish[0], date_finish[1] - 1, date_finish[2]).getTime();
                        break;
                    }
                }
            }
        }
        if(link){
            cal_view+='<li onclick="view('+link+',\'task\')" class="calendar_task'+(current_day==i?' calendar_active" id="calendar_today"':'"')+'>' +
            '<span class="month">'+array[i][2]+'</span><br>' +
            '<span class="day">'+array[i][0]+'</span><br>' +
            '<span class="week_day">'+array[i][1]+'</span></li>';
        }else{
            cal_view+='<li class="'+(current_day==i?' calendar_active" id="calendar_today"':'"')+'>' +
            '<span class="month">'+array[i][2]+'</span><br>' +
            '<span class="day">'+array[i][0]+'</span><br>' +
            '<span class="week_day">'+array[i][1]+'</span></li>';
        }
    }
    return(cal_view);
}

function io(array,callback,busy){
    if(TM['need_restart']){
        if(confirm('Система обновилась, для продолжения работы, необходимо обновить страницу!')){location.reload();}
        return false;
    }
    if(TM['BUSY']&&busy){
        // BUILD STACK
    }else {
        if(busy){
            TM['BUSY']=busy;
        }
        callback = callback ? callback : handler;
        query = JSON.stringify(array);
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4) {
                if (xmlhttp.status == 200) {
                    response = xmlhttp.responseText;
                    try {
                        var jsonObject = JSON.parse(response);
                    } catch (e) {
                        // handle error
                        dbg('ERROR PARSING RESPONSE FROM SERVER [' + callback.name + '] SOURCE: ' + response);
                        return false;
                    }
                    TM['OFFLINE'] = false;
                    callback(JSON.parse(response));
                    if(JSON.parse(response)[TM['BUSY']]) {
                        TM['BUSY'] = false;
                    }
                } else {
                    TM['OFFLINE'] = true;
                    callback();
                }
            }
        }
        xmlhttp.open('GET', '/ajax.php?query=' + query + '&ver=' + VERSION + '&rand=' + new Date().getTime(), true);
        xmlhttp.send();
    }
}

function get(objID) {
    if (document.getElementById) {return document.getElementById(objID);}
    else if (document.all) {return document.all[objID];}
    else if (document.layers) {return document.layers[objID];}
}

function logout(){
    document.getElementById('main').className='blur';
    io({"action":"logout"});
}

function set_vmode(mode){
    TM[TM['current_page'] + '_mode']=mode;
    document.getElementById('currentv').innerHTML=document.getElementById(TM[TM['current_page'] + '_mode']).innerHTML;
    gen_list();
}

function page(name,headgen,viewid){
    if(typeof PAGE[name]!='undefined') {
        if ((TM['current_page'] != name) || headgen) {
            get('main').className='blur';
            if (name == 'auth'||name == 'reg') {
                get('header').innerHTML = '';
                TM['upl_window']=false;
            } else {
                if ((TM['current_page'] == 'auth'||TM['current_page'] == 'reg') || headgen) {
                    get('header').innerHTML=PART['header'];//name=='lk'?PART['header_slim']:PART['header'];
                    get('user_name').innerHTML = TM['USER_NAME'];
                    get('user_pic').src = TM['USER_PIC'];
                    TM['upl_window']=false;
                    get('main').className='noblur';
                }
            }
            TM['highlight_day']=false;
            TM['highlight_element']=false;
            TM['comments_loaded']=false;
            TM['CID']=false;
            TM['empty_comments']=false;
            TM['tmp_group_add_line']=false;
            if(TM['upl_window']!=false){TM['upl_window'].window.close();}
            TM['upl_window']=false;
            document.title = PAGE[name]['title'] + ' | Flatsy';
            history.pushState(null,null,name);
            get('page').innerHTML = PAGE[name]['source'];
            TM['current_page'] = name;
            if (get('currentv')) {
                get('currentv').innerHTML = get(TM[TM['current_page'] + '_mode']).innerHTML;
            }
            if (get('email')) {
                get('email').focus();
            }
            if (get('calendar')&&page!='lk') {
                init_cal();
            }
            if (get('load_pic')) {
                get('load_pic').style = "display:none";
            }
            if (get('about')) {
                get('about').innerHTML='FLATSY v.'+FLATSY;
            }
            if(name=='tasks'||name=='projects'||name=='groups'){
                gen_list();
                if(!TM['AUID']) {
                    TM['AUID'] = setInterval('main()', 1000);
                }
                if(viewid){
                    type=name=='tasks'?'task':(name=='projects'?'project':'group');
                    view(viewid,type);
                }
            }
            // TESTING LOCAL STORAGE
            if(supports_html5_storage()){
                localStorage['TM']=JSON.stringify(TM);
            }
            //setTimeout(get('main').className='noblur',2000);
            get('main').className='noblur';
            //alert(name);
        }
        onclick();
        sizing();
    }
    return false;
}

function auth_send(response) {
    if (response) {
        //response = JSON.parse(response);
        if (response['auth'] == false) {
            //document.getElementById('pass').value='';
            //alert('Incorrect');
            document.getElementById('pass').placeholder='Неверный пароль!';
        } else {
            TM['UID']=response['auth']['id'];
            TM['USER_NAME']=response['auth']['full_name'];
            TM['USER_PIC']=response['auth']['photo'];
            TM['wait_load']=true;
            document.getElementById('load_pic').innerHTML='<div class="avatar"><img src="'+TM['USER_PIC']+'"></div>'+PART['loader'];
            document.getElementById('wrapper').style="transition: all 0.3s ease;-webkit-filter: blur(5px); -moz-filter: blur(5px); -o-filter: blur(5px); -ms-filter: blur(5px); filter: blur(5px);";
            document.getElementById('load_pic').style="position: absolute;left: 48.7%;top: 34.6%;z-index:9999";
            document.getElementById('wrapper').style.height = window.innerHeight-240+'px'
            //setTimeout("io({'action':'load_db'})",1000);
            io({'action':'load_db'});
        }
    }else{
        send = {
            "action": "auth",
            "email": document.getElementById('email').value,
            "pass": document.getElementById('pass').value
        };
        document.getElementById('pass').value='';
        //query = JSON.stringify(send);
        //Ajax('GET', '/ajax.php?query=' + query + '&rand=' + new Date().getTime(), 'authi');
        io(send,auth_send);
    }
};

function load_enter_pic(response){
    document.getElementById('pass').placeholder='Пароль';
    if(response){
        //response = JSON.parse(response);
        if(response['get_user']!=false){
            document.getElementById('pic').innerHTML='<div class="avatar"><img src="'+response['get_user']['photo']+'"></div>';
        }
    }else {
        if (TM['apic_loaded'] == false) {
            TM['apic_loaded']=true;
            io({"action": "get_user", "email": document.getElementById('email').value},load_enter_pic);
        }
    }
}

function reg_send(response) {
    if (response) {
        //response = JSON.parse(response);
        if (response['reg']) {
            if (response['reg'] == true) {
                document.getElementById('status').innerHTML = 'Вы успешно зарегистрированы!';
                page('auth');
                document.getElementById('email').value=TM['tmp_reg_login'];
                document.getElementById('pass').focus();
                TM['tmp_reg_login']=false;
            }else if(response['reg']=='Login exists') {
                document.getElementById('status').innerHTML = 'Почтовый адрес уже зарегистрирован';
            }else if(response['reg']=='Bad data'){
                document.getElementById('status').innerHTML = 'Вы ввели некорректные данные';
            }else if(response['reg']=='Empty data'){
                document.getElementById('status').innerHTML = 'Необходимо заполнить все поля';
            }else if(response['reg']=='Bad key'){
                document.getElementById('status').innerHTML = 'Недействительный код';
            }else{
                document.getElementById('status').innerHTML = 'Ошибка БД: ' +
                ''+response['reg'];
            }
        }
    } else {
        p1=get('password').value;
        p2=get('repeat_password').value;
        if(p1==p2){
            TM['tmp_reg_password']=p1;
        }else{
            TM['tmp_reg_password']=false;
        }
        e1=get('email').value;
        e2=get('repeat_email').value;
        if(e1==e2) {
            TM['tmp_reg_email'] = e1;
        }else {
            TM['tmp_reg_email'] = false;
        }
        if((get('email').value=='')&&(get('password').value=='')){
            get('status').innerHTML='Необходимо заполнить все поля';
            return;
        }else{
            if(!TM['tmp_reg_email']){
                document.getElementById('status').innerHTML = 'Адреса e-mail не совпадают';
                return;
            }
            if(!TM['tmp_reg_password']){
                document.getElementById('status').innerHTML = 'Пароли не совпадают';
                return;
            }
            if(!TM['tmp_reg_password']||!TM['tmp_reg_email']){
                document.getElementById('status').innerHTML = 'Необходимо заполнить все поля';
                return;
            }
        }
        if(get('code').value==''){
            get('status').innerHTML='Для подтверждения полномочий тестирующего, необходим инвайт-код';
            return;
        }
        send = {
            "action": "reg",
            "lastname": document.getElementById('lastname').value,
            "firstname": document.getElementById('firstname').value,
            "patronymic": document.getElementById('patronymic').value,
            "password": TM['tmp_reg_password'],
            "email": TM['tmp_reg_email'],
            "invite": get('code').value
        };
        TM['tmp_reg_login']=document.getElementById('email').value;
        TM['tmp_reg_email']=false;
        TM['tmp_reg_password']=false;
        io(send,reg_send);
    }
};

function reg_check(mode){
    if(mode=='password'){
        p1=get('password').value;
        p2=get('repeat_password').value;
        if(p1==p2){
            TM['tmp_reg_password']=p1;
        }else{
            TM['tmp_reg_password']=false;
        }
    }
    if(mode=='email'){
        e1=get('email').value;
        e2=get('repeat_email').value;
        if(e1==e2){
            TM['tmp_reg_email']=e1;
        }else{
            TM['tmp_reg_email']=false;
        }
    }
}

function offline(){
    if(TM['AUID']) {
        clearInterval(TM['AUID']);
    }
}

function supports_html5_storage() {
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
        return false;
    }
}

function clearnl(text,br){
    return text.replace(/(\n(\r)?)/g, (br?'<br>':' '));
}

function dbg(text){
    window.console.log(text);
}

function upload_pic_show(){
    if(!TM['upl_window']) {
        TM['upl_window']=window.open('','upload_pic',"width=420,height=230,menubar=no,location=no,resizable=no,scrollbars=yes,status=no");
        TM['upl_window'].document.write('<!DOCTYPE html><html><head><meta charset="utf-8"></head>' +
        '<body onunload="window.opener.TM[\'upl_window\']=false;"><div id="files"><form enctype="multipart/form-data" action="/upl.php" method="post"><p>' +
        '<input type="hidden" name="type" value="userpic">Загрузка нового аватара:<br/><br/>' +
        '<input class="file" onchange="document.forms[0].submit();" type="file" name="f"><br/><br/><input type="submit" value="Загрузить"></p></form> </div>' +
        '</body></html>');
    }
    onclick();
}

function crop(img){
    TM['crop_window']=window.open('/js/ext/crop.html?'+img,'crop_pic',"width=800,height=600,menubar=no,location=no,resizable=no,scrollbars=yes,status=no");
    TM['crop_window'].window.image=img;
}

function crop_save(crop){
    io({'action':'crop','crop':crop});
}

function str_replace(search, replace, subject) {
    return subject.split(search).join(replace);
}

function str_insert(into,insert,position) {
    return into.slice(0,position)+insert+into.slice(position);
}

function parseHash(text){
    lines=text.split('<br>');
    words=[];
    for(l in lines){
        if(l!=0){
            words[words.length]='<br>';
        }
        l_words=lines[l].split(' ');
        for(w in l_words) {
            words[words.length]=l_words[w];
        }
    }
    for(i in words){
        word=words[i];
        word=str_replace('%23','#',word);
        if (word.indexOf('#') == 0) {
            words[i] = '<a href="http://vk.com/feed?section=search&q=' + encodeURIComponent(word) + '" target="_blank">' + word + '</a>';
        }
    }
    return words.join(' ');
}

var urlParams;
(window.onpopstate = function () {
    var match,
        pl     = /\+/g,  // Regex for replacing addition symbol with a space
        search = /([^&=]+)=?([^&]*)/g,
        decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
        query  = window.location.search.substring(1);

    urlParams = {};
    while (match = search.exec(query))
        urlParams[decode(match[1])] = decode(match[2]);
})();

function getOffset(elem) {
    if (elem.getBoundingClientRect) {
        return getOffsetRect(elem)
    } else {
        return getOffsetSum(elem)
    }
}

function getOffsetSum(elem) {
    var top=0, left=0
    while(elem) {
        top = top + parseInt(elem.offsetTop)
        left = left + parseInt(elem.offsetLeft)
        elem = elem.offsetParent
    }
    return {top: top, left: left}
}

function getOffsetRect(elem) {
    var box = elem.getBoundingClientRect();
    var body = document.body;
    var docElem = document.documentElement;
    var scrollTop = window.pageYOffset || docElem.scrollTop || body.scrollTop;
    var scrollLeft = window.pageXOffset || docElem.scrollLeft || body.scrollLeft;
    var clientTop = docElem.clientTop || body.clientTop || 0;
    var clientLeft = docElem.clientLeft || body.clientLeft || 0;
    var top  = box.top +  scrollTop - clientTop;
    var left = box.left + scrollLeft - clientLeft;
    return { top: Math.round(top), left: Math.round(left) }
}