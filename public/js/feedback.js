$(document).ready(function(){
    //Отправляем данные формы
    $('#mailForm').submit(function(e){
        e.preventDefault();
        //Получаем объект формы
        var form = $('#mailForm');
            
        //Создаем прелоадер перед отправкой для красоты
        var preloader = '<img src="./img/35.gif" alt="preloader"> Отправляем письмо...';
        $('#respons').removeClass('hide').addClass('alert alert-warning').html(preloader)

        //Устанавливаем задержку перед отправкой на 1,5 сек
        setTimeout(function(){
            //Отправляем AJAX запрос на сервер
            $.ajax({
                //Каким методом отправляем данные POST или GET
                type: form.attr('method'),
                //Адрес скрипта обработчика
                url: form.attr('action'),
                //Данные с полей формы
                data: form.serialize(),
                //Данный в формате JSON
                dataType: "JSON",
                success: function( data ) {
                    //Получаем AJAX ответ от сервера
                    //Проверяем на ошибки
                    if(data.status == 0)
                    {
                        err = '<ul>'
                        $.each(data.err, function(key, val){
                            err += '<li>'+ val +'</li>'
                        })
                        err += '</ul>'
                        //Выводим ошибки
                        $('#respons').removeClass().addClass('alert alert-danger').html(err)
                    }
                    else
                    {
                        $('#respons').removeClass().addClass('alert alert-success').html(data.ok)
                        //Очищаем форму и скрываем ее
                        setTimeout(function(){
                            form.trigger('reset');
                            $('#respons').removeClass().addClass('hide').empty();
                            $('.bs-example-modal-lg').modal('hide');        
                        }, 1500)
                    }
                }
            })      
        }, 1500)
    })
})