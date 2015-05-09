var VERSION=61;
var TM={};
TM['current_page']=false;
TM['tasks_mode']='all';
TM['projects_mode']='all';
TM['update_db']=false;
TM['apic_loaded']=false;
TM['time_offset']=18000000;
TM['param_send']=false;
TM['months']=["января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"];
//TM['now'] = new Date(new Date().getTime()+TM['time_offset']).getTime();
TM['today']= TM.now;
TM['upl_window']=false;

tmpdate=new Date(new Date().getTime()+TM['time_offset']);
TM['now']=tmpdate.getTime();
TM['today']=new Date(tmpdate.getFullYear(), tmpdate.getMonth(), tmpdate.getDate()).getTime();
delete tmpdate;

tmpver=VERSION.toString();
FLATSY=tmpver.length<3?'0.':'';
for(i in tmpver){
    FLATSY+=(i==0?'':'.')+tmpver[i];
}
delete tmpver;

if(typeof SERVER !='undefined') {
    TM['UID'] = SERVER['ID'];
    TM['USER_NAME'] = SERVER['NAME'];
    TM['USER_PIC'] = SERVER['PIC'];
    TM['current_page'] = SERVER['PAGE']?SERVER['PAGE']:false;
}else{
    localStorage.clear();
}
//////////////////////////////////////
/*/ TESTING LOCAL STORAGE
if(supports_html5_storage()){
    if(localStorage['DB']){
        DB=JSON.parse(localStorage['DB']);
    }
    if(localStorage['TM']){
        TM=JSON.parse(localStorage['TM']);
        TM['AUID']=false;
    }
}
*//////////////////////////////////////
TM['update_db']=true;

function main(){
    io({"action":"check"});
    //TM['now'] = new Date(new Date().getTime()+TM['time_offset']).getTime();
    tmpdate=new Date(new Date().getTime()+TM['time_offset']);
    TM['now']=tmpdate.getTime();
    TM['today']=new Date(tmpdate.getFullYear(), tmpdate.getMonth(), tmpdate.getDate()).getTime();
    delete tmpdate;
}

function sizing() {
    if (document.getElementById('view')) {
        document.getElementById('view').style.height = window.innerHeight - document.getElementById('view').offsetTop + 'px';
    }
    if (TM['current_page'] == 'tasks') {
        document.getElementById('tasks').style.height = window.innerHeight - document.getElementById('tasks').offsetTop-160 + 'px';
    } else if (TM['current_page'] == 'projects') {
        document.getElementById('projects').style.height = window.innerHeight - document.getElementById('projects').offsetTop-160 + 'px';
    } else if (TM['current_page'] == 'groups') {
        document.getElementById('groups').style.height = window.innerHeight - document.getElementById('groups').offsetTop-160 + 'px';
    }
    if (document.getElementById('comments')) {
        // CORRECT HEIGHT
        document.getElementById('comments').style.height = window.innerHeight - document.getElementById('comments').offsetTop - document.getElementById('new_comm').scrollHeight-57 + "px"
    }
    if(TM['current_page']=='auth'){
        document.getElementById('wrapper').style.height = window.innerHeight-240+'px'
    }
    if(TM['current_page']=='reg'){
        document.getElementById('wrapper').style.height = window.innerHeight-100+'px'
    }
}

function init(arg) {
    TM['GET_PARAM']=urlParams;
    if (TM['UID']) {
        TM['current_page']=(TM['current_page']=='auth'||TM['current_page']=='reg')?'tasks':TM['current_page'];
        TM['current_page']=PAGE[TM['current_page']]?TM['current_page']:'tasks';
        if(TM['GET_PARAM']['hash']){
            if(TM['param_send']==true){
                if(arg){
                    arg=JSON.parse(arg);
                    if(!arg['parse_hash']) {
                        alert('Не удалось зарегистрировать ключ');
                        //alert(arg);
                    }
                    TM['current_page']='groups';
                }
                TM['wait_load']=true;
                TM['GET_PARAM']['hash']=false;
                io({'action':'load_db'});
            }else {
                document.getElementById('main').className = "blur";
                if (TM['update_db'] == false) {
                    page(TM['current_page'] == false ? 'tasks' : TM['current_page'], true);
                }
                TM['param_send']=true;
                io({"action": "parse_hash", "hash": TM['GET_PARAM']['hash']}, init);
            }
            return;
        }
        if(TM['update_db']==true){
            TM['wait_load']=true;
            document.getElementById('main').className="blur";
            io({'action':'load_db'});
        }else{
            page(TM['current_page']==false?'tasks':TM['current_page'],true);
        }
    }else{
        page('auth',false);
    }
}

function onclick(){
    if(TM['current_page']=='groups'&&TM['tmp_group_add_line']){
        TM['tmp_group_add_line']=false;
        get('add_line').innerHTML='<div class="plus"><div id="p1"></div><div id="p2"></div><div id="p3"></div><div id="p4"></div></div>Добавить группу';
    }
}

document.onkeyup = function (e) {
    e = e || window.event;
    //alert(e.keyCode);
    if(TM['current_page']=='auth') {
        if (e.keyCode === 13) {
            if (document.getElementById('pass').value != '') {
                auth_send();
            }
        }
    }
    if(TM['CID']) {
        if(e.keyCode==10||(e.ctrlKey&&e.keyCode==13)) {
            document.getElementById('new_comm').value+='\r\n';
        }
        if (e.keyCode == 13&&!e.ctrlKey) {
            add_comment();
        }
    }
    return false;
}
