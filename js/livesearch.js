var LSEARCH=[];

/*function loadStyles(action) {
    if (LSEARCH[action]['stylesl'] == false) {
        LSEARCH[action]['stylesl'] = true;
        document.body.innerHTML += '<style>' +
        '.search_advice_wrapper{' +
        'display:none;' +
        'width: 250px;' +
        'background-color: rgb(220,220,220);' +
        'color: rgb(50,50,50);' +
        '-moz-opacity: 0.95;' +
        'opacity: 0.95;' +
        '-ms-filter:"progid:DXImageTransform.Microsoft.Alpha"(Opacity=95);' +
        'filter: progid:DXImageTransform.Microsoft.Alpha(opacity=95);' +
        'filter:alpha(opacity=95);' +
        'z-index:999;' +
        'position: absolute;' +
        '}' +
        '.search_advice_wrapper .advice_variant{' +
        'cursor: pointer;' +
        'padding: 5px;' +
        'text-align: left;}' +
        '.search_advice_wrapper .advice_variant:hover{' +
        'color:#FEFFBD;' +
        'background-color:#818187;}' +
        '.search_advice_wrapper .active{' +
        'cursor: pointer;' +
        'padding: 5px;' +
        'color:#FEFFBD;' +
        'background-color:#818187;}' +
        '</style>';
    }
}*/

function loadSearch(input,wrapper,action,callback) {
    if(!LSEARCH[action]){
        LSEARCH[action]=[];
    }
    LSEARCH[action]['suggest_count'] = 0;
    LSEARCH[action]['input_initial_value'] = '';
    LSEARCH[action]['suggest_selected'] = 0;
    LSEARCH[action]['selected_id']=false;
    //LSEARCH[action]['stylesl']=false;
    //loadStyles(action);
    // читаем ввод с клавиатуры
    $(input).keyup(function(I){
        // определяем какие действия нужно делать при нажатии на клавиатуру
        switch(I.keyCode) {
            // игнорируем нажатия на эти клавишы
            case 13:  // enter
            case 27:  // escape
            case 38:  // стрелка вверх
            case 40:  // стрелка вниз
                break;

            default:
                // производим поиск только при вводе более 2х символов
                //if($(this).val().length>2){

                    LSEARCH[action]['input_initial_value'] = $(this).val();
                    if(action=='get_projects'){
                        var list=[];
                        for(p in DB['PROJECT']){
                            nm=DB['PROJECT'][p]['nameproject'].toLowerCase();
                            vl=$(this).val().toLowerCase();
                            if(nm.indexOf(vl) + 1){
                                list[list.length]={"id":DB['PROJECT'][p]['idproject'],"name":DB['PROJECT'][p]['nameproject']};
                            }
                        }
                        LSEARCH[action]['list']=list;
                        LSEARCH[action]['suggest_count'] = LSEARCH[action]['list'].length;
                        if (LSEARCH[action]['suggest_count'] > 0) {
                            $(wrapper).html("").show();
                            for (var i in LSEARCH[action]['list']) {
                                if (LSEARCH[action]['list'][i] != '') {
                                    $(wrapper).append('<div onclick="$(\'' + input + '\').val($(this).text()); LSEARCH[\'' + action + '\'][\'selected_id\']=\'' + LSEARCH[action]['list'][i]['id'] + '\';" class="advice_variant">' + LSEARCH[action]['list'][i]['name'] + '</div>');
                                }
                            }
                        }
                    }
                    if(action=='get_users'||action=='get_users_groups') {
                        stext = $(this).val().toLowerCase();
                        var list = [];
                        for (g in DB['GROUP']) {
                            if(action=='get_users_groups') {
                                gid = DB['GROUP'][g]['idgroup'];
                                name = DB['GROUP'][g]['namegroup'].toLowerCase();
                                if (name.indexOf(stext) + 1) {
                                    list[list.length] = {
                                        "id": gid,
                                        "type": "group",
                                        "name": 'Группа [' + DB['GROUP'][g]['namegroup'] + ']'
                                    };
                                }
                            }
                            for (u in DB['GROUP'][g]['users']) {
                                uid = DB['GROUP'][g]['users'][u]['id']
                                firstname = DB['GROUP'][g]['users'][u]['firstname'].toLowerCase();
                                lastname = DB['GROUP'][g]['users'][u]['lastname'].toLowerCase();
                                patronymic = DB['GROUP'][g]['users'][u]['patronymic'].toLowerCase();
                                if ((firstname.indexOf(stext) + 1) || (lastname.indexOf(stext) + 1) || (patronymic.indexOf(stext) + 1)) {
                                    exist = false;
                                    for (l in list) {
                                        if (list[l]['id'] == uid) {
                                            exist = true;
                                        }
                                    }
                                    if (exist == true) {
                                        continue;
                                    }
                                    list[list.length] = {
                                        "id": uid,
                                        "type": "user",
                                        "name": DB['GROUP'][g]['users'][u]['lastname'] + ' ' + DB['GROUP'][g]['users'][u]['firstname']
                                    };
                                }
                            }
                        }
                        LSEARCH[action]['list'] = list;
                        LSEARCH[action]['suggest_count'] = LSEARCH[action]['list'].length;
                        if (LSEARCH[action]['suggest_count'] > 0) {
                            $(wrapper).html("").show();
                            for (var i in LSEARCH[action]['list']) {
                                if(i>9){
                                    break;
                                }
                                if (LSEARCH[action]['list'][i] != '') {
                                    if (callback) {
                                        $(wrapper).append('<div onclick="get(\''+input.substr(1)+'\').value=\'\'; '+callback+'(\''+LSEARCH[action]['list'][i]['id']+'\',\''+LSEARCH[action]['list'][i]['name']+'\',\''+LSEARCH[action]['list'][i]['type']+'\'); clean_search(\''+action+'\');" class="advice_variant">' + LSEARCH[action]['list'][i]['name'] + '</div>');
                                    } else {
                                        $(wrapper).append('<div onclick="$(\'' + input + '\').val($(this).text()); LSEARCH[\'' + action + '\'][\'selected_id\']=\'' + LSEARCH[action]['list'][i]['id'] + '\'; LSEARCH[\'' + action + '\'][\'selected_type\']=\'' + LSEARCH[action]['list'][i]['type'] + '\';" class="advice_variant">' + LSEARCH[action]['list'][i]['name'] + '</div>');
                                    }
                                }
                            }
                        }
                    }else{
                        /*
                        // производим AJAX запрос к /ajax/ajax.php, передаем ему GET query, в который мы помещаем наш запрос
                        $.get('/ajax.php', 'query=' + JSON.stringify({
                            "action": action,
                            "query": $(this).val()
                        }) + '&ver='+VERSION, function (data) {
                            //php скрипт возвращает нам строку, ее надо распарсить в массив.
                            // возвращаемые данные: ['test','test 1','test 2','test 3']
                            var list = JSON.parse(data);
                            LSEARCH[action]['list'] = list['users'];
                            //var list = eval("("+data+")");
                            LSEARCH[action]['suggest_count'] = LSEARCH[action]['list'].length;
                            if (LSEARCH[action]['suggest_count'] > 0) {
                                // перед показом слоя подсказки, его обнуляем
                                $(wrapper).html("").show();
                                for (var i in LSEARCH[action]['list']) {
                                    if (LSEARCH[action]['list'][i] != '') {
                                        // добавляем слою позиции
                                        $(wrapper).append('<div onclick="$(\'' + input + '\').val($(this).text()); LSEARCH[\'' + action + '\'][\'selected_id\']=\'' + LSEARCH[action]['list'][i]['id'] + '\';" class="advice_variant">' + LSEARCH[action]['list'][i]['name'] + '</div>');
                                    }
                                }
                            }
                        }, 'html');
                        */
                    }
                //}
                break;
        }
    });

    //считываем нажатие клавишь, уже после вывода подсказки
    $(input).keydown(function(I){
        switch(I.keyCode) {
            // по нажатию клавишь прячем подсказку
            case 13: // enter
            case 27: // escape
                $(wrapper).hide();
                return false;
                break;
            // делаем переход по подсказке стрелочками клавиатуры
            case 38: // стрелка вверх
            case 40: // стрелка вниз
                I.preventDefault();
                if(LSEARCH[action]['suggest_count']){
                    //делаем выделение пунктов в слое, переход по стрелочкам
                    key_activate( I.keyCode-39 ,input,wrapper);
                }
                break;
        }
    });

    // делаем обработку клика по подсказке
    $('.advice_variant').on('click',function(){
        // ставим текст в input поиска
        $(input).val($(this).text());
        // прячем слой подсказки
        $(wrapper).fadeOut(350).html('');
    });

    // если кликаем в любом месте сайта, нужно спрятать подсказку
    $('html').click(function(){
        $(wrapper).hide();
    });
    // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
    $(input).click(function(event){
        //alert(suggest_count);
        if(LSEARCH[action]['suggest_count'])
            $(wrapper).show();
        event.stopPropagation();
    });
};

function clean_search(action){
    LSEARCH[action]['input_initial_value'] = '';
    LSEARCH[action]['list']=[];
    LSEARCH[action]['suggest_count'] = 0;
}

function key_activate(n,input,wrapper){
    $(wrapper+' div').eq(LSEARCH[action]['suggest_selected']-1).removeClass('active');

    if(n == 1 && LSEARCH[action]['suggest_selected'] < LSEARCH[action]['suggest_count']){
        LSEARCH[action]['suggest_selected']++;
    }else if(n == -1 && LSEARCH[action]['suggest_selected'] > 0){
        LSEARCH[action]['suggest_selected']--;
    }

    if( LSEARCH[action]['suggest_selected'] > 0){
        $(wrapper+' div').eq(LSEARCH[action]['suggest_selected']-1).addClass('active');
        $(input).val( $(wrapper+' div').eq(LSEARCH[action]['suggest_selected']-1).text() );
    } else {
        $(input).val( LSEARCH[action]['input_initial_value'] );
        LSEARCH[action]['selected_id']=false;
    }
}
