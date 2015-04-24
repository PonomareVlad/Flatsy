function init_comments(id,type){
    TM['comments_loaded']=type;
    TM['CID']=id;
    TM['empty_comments']=false;
    get('new_comm').focus();
    io({"action":"get_comments","id":id,"type":type},gen_comments);
}

function gen_comments(response){
    if(TM['OFFLINE']){
        get('comments').innerHTML='Оффлайн режим';
        return false;
    }
    offset=3600000*5;
    MONTH=["января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"];
    source='';
    date=new Date(new Date().getTime()+offset);
    now = new Date(date.getFullYear(), date.getMonth(), date.getDate()).getTime();
    if(response['comments']){
        comm=response['comments'];
        for(i in comm){
            datacom=comm[i]['datacom'].split(' ');
            datestring=datacom[0]+'T'+datacom[1];
            date= new Date(new Date(datestring).getTime()+offset);
            timecom=[(date.getHours()<10?'0':'')+date.getHours(),(date.getMinutes()<10?'0':'')+date.getMinutes()];
            datacom=[(date.getDate()<10?'0':'')+date.getDate(),date.getMonth()];
            datestring=new Date(date.getFullYear(), date.getMonth(), date.getDate()).getTime();
            source+='<div class="comment">' +
            '<img src="'+comm[i]['usercom_photo']+'"><div class="info_text">' +
            '<a href="javascript:void(0)" onclick=\'view('+comm[i]['usercom']+',"user")\'><div class="name">'+comm[i]['usercom_name']+'</div></a>' +
            '<div class="date">'+(now==datestring?('сегодня в '+timecom[0]+':'+timecom[1]):datacom[0]+' '+MONTH[parseInt(datacom[1])])+'</div>' +
            '<p class="text">'+comm[i]['comment']+'</p></div></div>';
        }
    }
    if(source==''){source='<div class="comment"><p class="text">(Комментариев нет)</p></div>';
        TM['empty_comments']=true;
    }else{
        if (TM['current_page'] == 'tasks') {
            get('cloud_' + TM['CID']).innerHTML = '<img src="templates/default/images/dia.png">';
        }
    }
    document.getElementById('comments').innerHTML=source;
    document.getElementById('comments').scrollTop=9999;
}

function add_comment(){
    text=document.getElementById('new_comm').value;
    document.getElementById('new_comm').value='';
    if(TM['ufiles'].length==0){
        files=false;
    }else{
        files={};
        for(i in TM['ufiles']){
            files[i]=TM['ufiles'][i];
        }
    }
    io({"action":"add_comment","id":TM['CID'],"type":TM['comments_loaded'],"text":text,"files":files});
}

function reset_comments(){
    TM['comments_loaded']=false;
    TM['CID']=false;
    TM['empty_comments']=false;
}