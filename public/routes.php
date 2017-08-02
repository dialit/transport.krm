<?php

    // конфигурация
    require("../includes/config.php"); 

    // запрашивать базу данных transport для пользователя
    $k = str_replace('\'','',$_GET["id"] );
    $rows = query("SELECT `id`,`type`,`n_marshr`,`nach_kon`,`cena`,`int_dvij`,`rej_raboty`,`vr_raboty`,`front`,`back`,`firma`,`rasp` FROM `transport` WHERE `id` = ?",$k);
    
    //var_dump($k);
    $transport = $rows[0];
    $front = $rows[0]["front"];//список id остановок по маршруту
    //var_dump($front);
    $back = $rows[0]["back"];//список id остановок по маршруту в обратном направлении
    //var_dump($back);
    
    //создаём массив из front  
    $tok = strtok($front, ",");
    $mas_front = [];
    while ($tok !== false) 
    {
        $mas_front[]=$tok;
        $tok = strtok(",");
    }
    //создаём массив из back  
    $tok = strtok($back, ",");
    $mas_back = [];
    while ($tok !== false) 
    {
        $mas_back[]=$tok;
        $tok = strtok(",");
    }
    //var_dump($mas_front);
    //var_dump($mas_back);
    
?>
<head><script src='https://www.google.com/recaptcha/api.js'></script></head> 

<div class="container-fluid">
    <h3><?= $transport["type"] ?>&nbsp;№<?= $transport["n_marshr"] ?></h3>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#panel1">Описание</a></li>
        <li><a data-toggle="tab" href="#panel2">Расписание</a></li>
        <li><a data-toggle="tab" href="#panel3">Комментарии</a></li>
        <li><a data-toggle="tab" href="#panel4">Новости</a></li>
    </ul>

    <div class="tab-content">
        <div id="panel1" class="tab-pane fade in active">
            <div class="table-responsive">
           <table class="table table-striped table-bordered table-hover">
              <thead>
                  <th>Стоимость проезда:</th>
                  <th>Интервал движения:</th>
                  <th>Дни работы:</th>
                  <th>Время работы:</th>
                  <th>Конечные остановки:</th>
              </thead>
               <tbody>
                   <tr>
                       <td><?= $transport["cena"] ?></td>
                       <td><?= $transport["int_dvij"] ?></td>
                       <td><?= $transport["rej_raboty"] ?></td>
                       <td><?= $transport["vr_raboty"] ?></td>
                       <td><?= $transport["nach_kon"] ?></td>
                   </tr>
               </tbody>
           </table>
            </div>
<!--            <div class="table-responsive">-->
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <th>Путь следования:</th>
                    <th>Обратный путь:</th>
                </thead>
                <tbody>
                    <tr>
                        <td>
                                <?php 
                                    //перебираем массив с id остановок и запрашиваем по ним названия остановок из базы
                                    //var_dump($front);
                                    foreach ($mas_front as $value){
	    				                //var_dump($value);
                                        $namestops = query("SELECT `stops_name` FROM `stops` WHERE `id` = ?", $value);
                                        $names = $namestops[0]["stops_name"];
	                                    //выводим названия остановок и html ссылки под названием остановки c id остановок
	                                    echo "<a href='javascript:update(5, $value)' title='Показать остановку'>$names</a>, ";
                                    }
                                    unset($value);
                                ?>
                       </td>
                       <td>
                                <?php 
                                    //перебираем массив с id остановок и запрашиваем по ним названия остановок из базы
                                    foreach ($mas_back as $value){
	    				                $namestops = query("SELECT `stops_name` FROM `stops` WHERE `id` = ?", $value);
	                                    $names = $namestops[0]["stops_name"];
                                        //выводим названия остановок и html ссылки под названием остановки c id остановок
	                                    echo "<a href='javascript:update(5, $value)' title='Показать остановку'>$names</a>, ";
                                    }
                                    unset($value);
                                ?>
                       </td>
                    </tr>
                </tbody>
            </table>
<!--            </div>-->
<!--               <div class="table-responsive">-->
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <td>Маршрут обслуживает: <?= $transport["firma"] ?></td>
                            <td><i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>
                            <a href='javascript:draw_marshr(3,<?=$k?>)'>Показать маршрут на карте</a></td>
                        </tr>
                    </tbody>
                </table>
<!--            </div>-->
        </div><!-- End content Tab1 -->
        
        <div id="panel2" class="tab-pane fade">           
            <iframe src="<?= $transport["rasp"] ?>" frameborder="0" width="100%" height="600px"></iframe>
        </div><!-- End content Tab2 -->
        
        <div id="panel3" class="tab-pane fade">
            <form method="POST" action="comments.php" enctype="multipart/form-data" name="form" onSubmit="return false">
                <div class="form-group">
                    <input type="hidden" class="form-control" name="<?= $transport["n_marshr"] ?>" value="<?= $transport["n_marshr"] ?>">
                    <label for="labelName">Имя:</label>
                    <input type="text" class="form-control" name="author" id="author" required>
                </div>
<!--
                <div class="form-group">
                    <label for="labelTema">Тема комментария:</label>
                    <input type="text" class="form-control" name="tems" id="tema">
                </div>
-->
                <div class="form-group">
                    <label for="labelText">Текст комментария:</label>
                    <textarea name="comment" id="message" cols="30" rows="10" class="form-control"></textarea>
                </div>
                 <div class="g-recaptcha" data-sitekey="6LciQCsUAAAAAMXAYzfYUEBS9oiH0StRVln2IE1e"></div><br />
                  <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
                <?php
                    $fp = fopen("comment.txt", "r"); // Открываем файл в режиме чтения
                    if ($fp) {
                        while (!feof($fp)) {
                            $mytext = fgets($fp, 999);
                            echo $mytext."<br />";
                        }
                    }
                ?>
        </div><!-- End content Tab3 -->
        
        <div id="panel4" class="tab-pane fade">
        <h4>До конца года Краматорску обещают еще 11 новых троллейбусов</h4>
        <img src="http://vp.donetsk.ua/images/2015/03/07787b3a9240560c7c80a80fe42e7642.jpg" alt="" class="responsive thumbnail">
        <div class="text-justify">
        <p>Большая часть транспортных средств будет закуплена за средства бюджета Краматорска, а остальные за средства областной казны.</p><p>По информации чиновников разрешился вопрос с определением победителя в системе электронных торгов Prozorro по приобретению 7 троллейбусов за счет городского бюджета. Кроме того, за счет средств обладминистрации будет приобретено еще 4 новых троллейбуса для Краматорска. Все одиннадцать транспортных средств ожидается получить до конца осени.</p><p>Ранее «Восточный проект» сообщал, что сотрудники Службы безопасности пресекли государственные убытки на 30 миллионов гривен во время тендера по закупке общественного транспорта для коммунального предприятия.</p><p>"Во время проверки документации коммерческих структур правоохранители установили, что участие в торгах принимают связанные между собой компании, - говорится в сообщении СБУ. - Дельцы сознательно создали предпосылки для закупки за бюджетные средства продукции по значительно завышенной цене. СБУ проинформировало тендерный комитет коммунального предприятия о выявленных фактах, который по материалам спецслужбы отменил результаты торгов".</p><p>Тогда два участника из четырех были отстранены от участия в торгах, в связи с проблемами в документации. Один из них обратился по поводу отказа от участия в конкурсе в Антимонопольный комитет.</p><p>Сейчас на дороги Краматорска коммунальным предприятием КТТУ ежедневно выпускается 25 троллейбусов и 9 автобусов. Увеличение количества транспортных средств позволить не только уменьшить интервал движения, но и увеличить время работы на маршруте.</p><p>Кроме того, ожидается организация новых маршрутов. В частности речь идет о том, чтобы организовать маршрут №8 «Железнодорожный вокзал – УТСЗН». Для реализации задуманного необходимо около 1,5 млн. грн. За эти средства будет организована перемычка для левого поворота на перекрестке ул. Парковая и ул. Стуса. Как рассказал заместитель городского головы Сергей Боевский, дороговизна проекта связана с его сложностью и необходимостью установки 10 опор.</p><p>К слову, предложение выделении 1,45 млн. грн. на капремонт контактной сети под эти нужны выносился на заседание исполкома и июльскую сессию. По итогам рассмотрения вопроса было принято решение отложить выделение средств.</p></div>
        
        </div><!-- End content Tab4 -->
    </div>
</div>