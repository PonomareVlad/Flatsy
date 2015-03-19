var TASK=[];
var Show_it=false;
var postload_show=false;
var comments=false;
var new_comm=false;

function main(){
    check();
    if(comments!=false){
        if(document.getElementById('comments')) {
            io({"action": "get_comments", "id": comments}, 'gen_comments');
        }else{
            comments=false;
        }
    }
}

function sizing(){
    document.getElementById('view').style.height=window.innerHeight-document.getElementById('view').offsetTop+'px';
    document.getElementById('tasks').style.height=window.innerHeight-document.getElementById('tasks').offsetTop+'px';
    if(comments!=false){
        document.getElementById('comments').style.height=window.innerHeight-document.getElementById('comments').offsetTop-document.getElementById('new_comm').scrollHeight-12+"px"
    }
}

function init() {
    if (auth==true) {
        //io({"action":"init"},'postinit');
        check('all');
        tasks_mode=document.getElementById('view_mode').value;
        // AutoUpdate
        setInterval('main()', 5000);

        sizing();

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
}

//function postinit(response){}