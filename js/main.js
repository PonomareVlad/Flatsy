var TASK=[];
var Show_it=false;
var postload_show=false;

function init() {
    if (auth==true) {
        //io({"action":"init"},'postinit');
        check('all');
        tasks_mode=document.getElementById('view_mode').value;
        // AutoUpdate
        setInterval('check()', 5000);

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