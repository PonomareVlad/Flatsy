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
    if(TM['current_page']!=name||headgen) {
        if (name == 'auth') {
            document.getElementById('header').innerHTML = '';
        } else {
            if (TM['current_page'] == 'auth' || headgen) {
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
    }
}

function sort(){

}