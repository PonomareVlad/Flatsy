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
            eval(callback+'(\''+xmlhttp.responseText+'\');');
        }
    }
    xmlhttp.open(method,url,true);
    xmlhttp.send();
}

function handler(response) {
    response = JSON.parse(response);
    if (response['auth']==true) {
        auth=true;
        if (response['tasks']) {
            tasks = new Array();
            for (i in response['tasks']) {
                tasks[i] = new Array();
                for (j in response['tasks'][i]) {
                    tasks[i][j] = response['tasks'][i][j];
                }
            }
        }
    }else{
        if(auth==true){
            auth=false;
            window.location='/auth';
        }
    }
}

function check(mode){
    send={"check":mode||"all"}
    query=JSON.stringify(send);
    Ajax('GET','/ajax.php?query='+query+'&rand='+new Date().getTime(),'handler');
}

function logout(){
    send={"action":"logout"}
    query=JSON.stringify(send);
    Ajax('GET','/ajax.php?query='+query+'&rand='+new Date().getTime(),'handler');
}

function main(){
    check();
}

function init() {
    if (auth) {
        // AutoUpdate
        setInterval('main()', 5000);

        // Calendar generation
        D1 = new Date();
        D1last = new Date(D1.getFullYear(),D1.getMonth()+1,0).getDate(); // последний день месяца
        month=["Янв","Фев","Мар","Апр","Май","Июн","Июл","Авг","Сен","Окт","Ноя","Дек"]; // название месяца, вместо цифр 0-11
        days=['вс','пн','вт','ср','чт','пт','сб'];
        array=[];
        view='<ul>';
        for(var  i = 1; i <= D1last; i++) {
            tmp=array.length;
            array[tmp]=[i,days[new Date(D1.getFullYear(),D1.getMonth(),i).getDay()],month[D1.getMonth()]];
            if (i == D1.getDate()) {
                current_day=tmp;
            }
        }
        for(i in array) {
            view+='<li><span class="month">'+array[i][2]+'</span><br>' +
            '<span class="day">'+array[i][0]+'</span><br>' +
            '<span class="week_day">'+array[i][1]+'</span></li>';
        }
        document.getElementById('calendar').innerHTML=view+'</ul>';
    }
}

