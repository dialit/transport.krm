<?php

    // конфигурация
    require("../includes/config.php"); 

    // запрашивать базу данных transport для пользователя

    $rows = query("SELECT * FROM `transport` WHERE `id` = ?",$_GET["id"]);
    $k = str_replace('\'','',$_GET["id"] );
    //var_dump($k);
    $transport = $rows[0];
    foreach ($rows as $row){
        $front = $row["front"];//список id остановок по маршруту
        $back = $row["back"];//список id остановок по маршруту в обратном направлении
    }
    unset($rows);//очистить переменную
    unset($row);
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
                                    foreach ($front as $value){
	    				                $namestops = query("SELECT `stops_name` FROM `stops` WHERE `id` = ?", $value);
	    				                $names = $namestops[0]["id"];
	                                    //выводим названия остановок и html ссылки под названием остановки c id остановок
	                                    echo "<a href='javascript:update(5, $value)' title='Показать остановку'>$names,</a>";
                                    }
                                    unset($value);
                                ?>
                       </td>
                       <td>
                                <?php 
                                    //перебираем массив с id остановок и запрашиваем по ним названия остановок из базы
                                    foreach ($back as $value){
	    				                $namestops = query("SELECT `stops_name` FROM `stops` WHERE `id` = ?", $value);
	    				                $names = $namestops[0]["id"];
	                                    //выводим названия остановок и html ссылки под названием остановки c id остановок
	                                    echo "<a href='javascript:update(5, $value)' title='Показать остановку'>$names,</a>";
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