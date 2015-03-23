/*
var TASK=[];
var Show_it=false;
var postload_show=false;
var comments=false;
var new_comm=false;
var view_mode='all';
*/


var TM=[];
TM['current_page']=false;
TM['tasks_mode']='all';
TM['projects_mode']='all';
TM['update_db']=false;
if(SERVER) {
    TM['USER_ID'] = SERVER['ID'];
    TM['USER_NAME'] = SERVER['NAME'];
    TM['USER_PIC'] = SERVER['PIC'];
    TM['current_page'] = SERVER['PAGE']?SERVER['PAGE']:false;
}
if(!DB){
    var DB=[];
    TM['update_db']=true;
}

function main(){
    io({"action":"check"});
    /*
    check();
    if(comments!=false){
        if(document.getElementById('comments')) {
            io({"action": "get_comments", "id": comments}, 'gen_comments');
        }else{
            comments=false;
        }
    }
    */
}

function sizing() {
    /*
    document.getElementById('view').style.height = window.innerHeight - document.getElementById('view').offsetTop + 'px';
    if (current_page == '/projects') {
        document.getElementById('projects').style.height = window.innerHeight - document.getElementById('projects').offsetTop + 'px';
    }else{
        document.getElementById('tasks').style.height = window.innerHeight - document.getElementById('tasks').offsetTop + 'px';
    }
    if (comments != false) {
        document.getElementById('comments').style.height = window.innerHeight - document.getElementById('comments').offsetTop - document.getElementById('new_comm').scrollHeight - 12 + "px"
    }
    */
}

function init() {
    sizing();
    if (TM['USER_ID']) {

        page(TM['current_page']!=false?TM['current_page']:'tasks',true);

        if(TM['update_db']==true){
            io({'action':'load_db'});
        }else{
            gen_list();
        }

        // AutoUpdate
        if(!TM['AUID']) {
            TM['AUID'] = setInterval('main()', 1000);
        }

        //document.getElementById('user_name').innerHTML=TM['USER_NAME'];
        //document.getElementById('user_pic').src=TM['USER_PIC'];

        //io({"action":"init"},'postinit');
        //check('all');

        /*
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
        */
    }else{
        page('auth',false);
    }
}

//function postinit(response){}