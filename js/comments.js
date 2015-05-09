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
    //offset=TM['time_offset'];
    MONTH=["января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"];
    source='';
    //date=new Date(TM.now);
    if(response['comments']){
        comm=response['comments'];
        for(i in comm){
            datacom=comm[i]['datacom'];
            datestring=parseInt(datacom);
            tmpdate= new Date(new Date(datestring).getTime());
            timecom=[(tmpdate.getHours()<10?'0':'')+tmpdate.getHours(),(tmpdate.getMinutes()<10?'0':'')+tmpdate.getMinutes()];
            datacom=[(tmpdate.getDate()<10?'0':'')+tmpdate.getDate(),tmpdate.getMonth()];
            datestring=new Date(tmpdate.getFullYear(), tmpdate.getMonth(), tmpdate.getDate()+1).getTime();
            source+='<div class="comment">' +
            '<img src="'+comm[i]['usercom_photo']+'"><div class="info_text">' +
            '<a href="javascript:void(0)" onclick=\'view('+comm[i]['usercom']+',"user")\'><div class="name">'+comm[i]['usercom_name']+'</div></a>' +
            '<div class="date">'+(TM.today==datestring?('сегодня в '+timecom[0]+':'+timecom[1]):datacom[0]+' '+MONTH[parseInt(datacom[1])])+'</div>' +
            '<p class="text">'+parseHash(comm[i]['comment'])+'</p></div></div>';
            delete tmpdate;
            delete datestring;
        }
        delete comm;
    }
    if(source==''){source='<div class="comment"><p class="text">(Комментариев нет)</p></div>';
        TM['empty_comments']=true;
    }else{
        if (TM['current_page'] == 'tasks') {
            if(get('cloud_' + TM['CID'])){get('cloud_' + TM['CID']).innerHTML = '<img src="templates/default/images/dia.png">';}
        }
    }
    document.getElementById('comments').innerHTML=source;
    document.getElementById('comments').scrollTop=9999;
}

function add_comment(){
    text=get('new_comm').value;
    text=encodeURIComponent(str_replace('#','?HASH?',text.replace(/\n$/m,' ')));
    if(text==''||text=='%20'){
        get('new_comm').value='';
        return false;
    }
    get('new_comm').value='';
    /*if(TM['ufiles'].length==0){
        files=false;
    }else{
        files={};
        for(i in TM['ufiles']){
            files[i]=TM['ufiles'][i];
        }
    }*/
    io({"action":"add_comment","id":TM['CID'],"type":TM['comments_loaded'],"text":text});
}

function reset_comments(){
    TM['comments_loaded']=false;
    TM['CID']=false;
    TM['empty_comments']=false;
}