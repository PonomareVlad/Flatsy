var suggest_count = 0;
var input_initial_value = '';
var suggest_selected = 0;
var selected_id=false;
var stylesl=false;

function loadStyles() {
    if (stylesl == false) {
        stylesl = true;
        document.body.innerHTML += '<style>' +
        '#search_advice_wrapper{' +
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
        '#search_advice_wrapper .advice_variant{' +
        'cursor: pointer;' +
        'padding: 5px;' +
        'text-align: left;}' +
        '#search_advice_wrapper .advice_variant:hover{' +
        'color:#FEFFBD;' +
        'background-color:#818187;}' +
        '#search_advice_wrapper .active{' +
        'cursor: pointer;' +
        'padding: 5px;' +
        'color:#FEFFBD;' +
        'background-color:#818187;}' +
        '</style>';
    }
}

function loadSearch() {
    loadStyles();
    // читаем ввод с клавиатуры
    $(".livesearch").keyup(function(I){
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
                if($(this).val().length>2){

                    input_initial_value = $(this).val();
                    // производим AJAX запрос к /ajax/ajax.php, передаем ему GET query, в который мы помещаем наш запрос
                    $.get('/ajax.php', 'query='+JSON.stringify({"action":"get_users","query":$(this).val()})+'',function(data){
                        //php скрипт возвращает нам строку, ее надо распарсить в массив.
                        // возвращаемые данные: ['test','test 1','test 2','test 3']
                        var list = JSON.parse(data);
                        list=list['users'];
                        //var list = eval("("+data+")");
                        suggest_count = list.length;
                        if(suggest_count > 0){
                            // перед показом слоя подсказки, его обнуляем
                            $("#search_advice_wrapper").html("").show();
                            for(var i in list){
                                if(list[i] != ''){
                                    // добавляем слою позиции
                                    $('#search_advice_wrapper').append('<div onclick="$(\'.livesearch\').val($(this).text()); selected_id='+list[i]['id']+';" class="advice_variant">'+list[i]['lastname']+' '+list[i]['firstname']+'</div>');
                                }
                            }
                        }
                    }, 'html');
                }
                break;
        }
    });

    //считываем нажатие клавишь, уже после вывода подсказки
    $(".livesearch").keydown(function(I){
        switch(I.keyCode) {
            // по нажатию клавишь прячем подсказку
            case 13: // enter
            case 27: // escape
                $('#search_advice_wrapper').hide();
                return false;
                break;
            // делаем переход по подсказке стрелочками клавиатуры
            case 38: // стрелка вверх
            case 40: // стрелка вниз
                I.preventDefault();
                if(suggest_count){
                    //делаем выделение пунктов в слое, переход по стрелочкам
                    key_activate( I.keyCode-39 );
                }
                break;
        }
    });

    // делаем обработку клика по подсказке
    $('.advice_variant').on('click',function(){
        // ставим текст в input поиска
        $('.livesearch').val($(this).text());
        // прячем слой подсказки
        $('#search_advice_wrapper').fadeOut(350).html('');
    });

    // если кликаем в любом месте сайта, нужно спрятать подсказку
    $('html').click(function(){
        $('#search_advice_wrapper').hide();
    });
    // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
    $('.livesearch').click(function(event){
        //alert(suggest_count);
        if(suggest_count)
            $('#search_advice_wrapper').show();
        event.stopPropagation();
    });
};

function key_activate(n){
    $('#search_advice_wrapper div').eq(suggest_selected-1).removeClass('active');

    if(n == 1 && suggest_selected < suggest_count){
        suggest_selected++;
    }else if(n == -1 && suggest_selected > 0){
        suggest_selected--;
    }

    if( suggest_selected > 0){
        $('#search_advice_wrapper div').eq(suggest_selected-1).addClass('active');
        $(".livesearch").val( $('#search_advice_wrapper div').eq(suggest_selected-1).text() );
    } else {
        $(".livesearch").val( input_initial_value );
        selected_id=false;
    }
}