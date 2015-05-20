var VERSION=63;
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

/*if(typeof SERVER !='undefined') {
    TM['UID'] = SERVER['ID'];
    TM['USER_NAME'] = SERVER['NAME'];
    TM['USER_PIC'] = SERVER['PIC'];
}else{
    chrome.storage.sync.clear();
}*/

TM['current_page'] = location.pathname.substr(1)!=''?location.pathname.substr(1):false;

if(location.protocol=='file:'||chrome){
    TM['LOCAL']=true;
}
//////////////////////////////////////
/*/ TESTING LOCAL STORAGE
if(supports_html5_storage()){
    if(chrome.storage.sync['DB']){
        DB=JSON.parse(chrome.storage.sync['DB']);
    }
    if(chrome.storage.sync['TM']){
        TM=JSON.parse(chrome.storage.sync['TM']);
        TM['AUID']=false;
    }
}
*//////////////////////////////////////
//TM['update_db']=true;

window.onload=init;
window.onresize=sizing;

parseParams();
window.onpopstate=function(){historyNav();};

function main(){
    if(!TM['need_restart']) {
        if(getCookie('invite_hash')) {io({"action": "parse_hash", "hash": getCookie('invite_hash')});deleteCookie('invite_hash');}
        io({"action": "check"});
    }
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
        document.getElementById('wrapper').style.height = window.innerHeight-240+'px';
        get('faq').style.right=window.innerWidth-get('authbox').offsetWidth-get('authbox').offsetLeft+5+'px';
        get('faq').style.bottom=window.innerHeight-get('authbox').offsetHeight-get('authbox').offsetTop+15+'px';
    }
    if(TM['current_page']=='reg'){
        document.getElementById('wrapper').style.height = window.innerHeight-100+'px';
        get('faq').style.right=window.innerWidth-get('authbox').offsetWidth-get('authbox').offsetLeft+5+'px';
        get('faq').style.bottom=window.innerHeight-get('authbox').offsetHeight-get('authbox').offsetTop+15+'px';
    }
}

function init() {
    io({'action': 'init'});
    if (TM['GET_PARAM']['invite_hash']) {
        setCookie('invite_hash', TM['GET_PARAM']['invite_hash']);
        if(!TM['LOCAL']){history.replaceState(null,null,TM['current_page']||'/');}
        delete TM['GET_PARAM']['hash'];
    }else{
        TM['QUERY']=location.search;
        if(!TM['LOCAL']){history.replaceState(null,null,TM['current_page']||'/');}
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
}

function onclick(){
    if(TM['current_page']=='groups'&&TM['tmp_group_add_line']){
        TM['tmp_group_add_line']=false;
        get('add_line').innerHTML='<div class="plus"><div id="p1"></div><div id="p2"></div><div id="p3"></div><div id="p4"></div></div>Добавить группу';
    }
}
