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

<div class="container-fluid">
       <div class="row">
           <div class="col-md-12">
               <h3><?= $transport["type"] ?>&nbsp;№<?= $transport["n_marshr"] ?></h3>
               <p><strong>Описание</strong>&nbsp;|&nbsp;
                <a href='javascript:draw_marshr(3,<?=$k?>)' title="Вернуться на карту">Схема маршрута</a>&nbsp;|&nbsp;
                <a href="kniga.php?id=<?=$k?>&c=" title="Отзывы о сервисе на маршруте...">Книга жалоб и предложений</a>&nbsp;|&nbsp;
                <a href="news" title="Посмотреть все новости, связанные с данным маршрутом...">Новости по теме</a></p>
           </div>
       </div>
       <div class="row">
           <div class="col-md-12">
           <table class="table table-striped table-bordered table-hover">
              <thead>
                  <th>Стоимость проезда:</th>
                  <th>Интервал движения:</th>
                  <th>Дни работы:</th>
                  <th>Время работы:</th>
                  <th>Конечные станции:</th>
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
                                        $namestops = query("SELECT `id`,`stops_name` FROM `stops` WHERE `id` = ?", $value);
	    				                //var_dump($namestops);
                                        $id = $namestops[0]["id"];
                                        $names = $namestops[0]["stops_name"];
	                                    //выводим названия остановок и html ссылки под названием остановки c id остановок
	                                    echo "<a href='javascript:update(5, $id)' title='Показать остановку'>$names,</a>";
                                    }
                                    unset($value);
                                ?>
                       </td>
                       <td>
                                <?php 
                                    //перебираем массив с id остановок и запрашиваем по ним названия остановок из базы
                                    foreach ($mas_back as $value){
	    				                $namestops = query("SELECT `id`,`stops_name` FROM `stops` WHERE `id` = ?", $value);
	    				                $id = $namestops[0]["id"];
	                                    $names = $namestops[0]["stops_name"];
                                        //выводим названия остановок и html ссылки под названием остановки c id остановок
	                                    echo "<a href='javascript:update(5, $id)' title='Показать остановку'>$names,</a>";
                                    }
                                    unset($value);
                                ?>
                       </td>
                    </tr>
                </tbody>
            </table>
            <p>Маршрут обслуживает:<?= $transport["firma"] ?></p>            
            <h3>Расписание</h3>
            <iframe src="<?= $transport["rasp"] ?>" frameborder="0" width="100%" height="100%"></iframe>
        </div>
    </div>
</div>