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
            response=xmlhttp.responseText;
            //eval(callback+'(\''+response+'\');');
            callback(response);
        }
    }
    xmlhttp.open(method,url,true);
    xmlhttp.send();
}

function init_cal(){
    // Calendar generation
    D1 = new Date();
    D1last = new Date(D1.getFullYear(),D1.getMonth()+1,0).getDate(); // последний день месяца
    month=["Янв","Фев","Мар","Апр","Май","Июн","Июл","Авг","Сен","Окт","Ноя","Дек"]; // название месяца, вместо цифр 0-11
    days=['вс','пн','вт','ср','чт','пт','сб'];
    array=[];
    cal_view='<ul>';
    for(var  i = 1; i <= D1last; i++) {
        tmp=array.length;
        array[tmp]=[i,days[new Date(D1.getFullYear(),D1.getMonth(),i).getDay()],month[D1.getMonth()]];
        if (i == D1.getDate()) {
            current_day=tmp;
        }
    }
    for(i in array) {
        cal_view+='<li><span class="month">'+array[i][2]+'</span><br>' +
        '<span class="day">'+array[i][0]+'</span><br>' +
        '<span class="week_day">'+array[i][1]+'</span></li>';
    }
    document.getElementById('calendar').innerHTML=cal_view+'</ul>';
}

function io(array,callback){
    query=JSON.stringify(array);
    Ajax('GET','/ajax.php?query='+query+'&rand='+new Date().getTime(),callback?callback:handler);
}

function logout(){
    io({"action":"logout"});
}

function set_vmode(mode){
    TM[TM['current_page'] + '_mode']=mode;
    document.getElementById('currentv').innerHTML=document.getElementById(TM[TM['current_page'] + '_mode']).innerHTML;
    gen_list();
}

function page(name,headgen){
    if((TM['current_page']!=name)||headgen) {
        if (name == 'auth') {
            document.getElementById('header').innerHTML = '';
        } else {
            if ((TM['current_page'] == 'auth') || headgen) {
                document.getElementById('header').innerHTML = PART['header'];
                document.getElementById('user_name').innerHTML = TM['USER_NAME'];
                document.getElementById('user_pic').src = TM['USER_PIC'];
            }
        }
        document.title = PAGE[name]['title'] + ' | EasyTM';
        document.getElementById('page').innerHTML = PAGE[name]['source'];
        TM['current_page'] = name;
        if (document.getElementById('currentv')) {
            document.getElementById('currentv').innerHTML = document.getElementById(TM[TM['current_page'] + '_mode']).innerHTML;
        }
        if(document.getElementById('email')){
            document.getElementById('email').focus();
        }
        if(document.getElementById('calendar')){
            init_cal();
        }
        if(document.getElementById('load_pic')){
            document.getElementById('load_pic').style="display:none";
        }
    }
}

function sort(){

}

function auth_send(response) {
    if (response) {
        response = JSON.parse(response);
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
            document.getElementById('wrapper').style="-webkit-filter: blur(5px); -moz-filter: blur(5px); -o-filter: blur(5px); -ms-filter: blur(5px); filter: blur(5px);";
            document.getElementById('load_pic').style="position: absolute;left: 49%;top: 47%;z-index:9999";

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
        response = JSON.parse(response);
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