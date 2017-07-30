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
    $mfront = explode(",",$front);
    $mback = explode(",",$back);
    unset($rows);//очистить переменную
    unset($row);
    
    //формируем перечень названий остановок по маршруту
    $i=0;
    foreach ($mfront as $value){
        $rowsfront = query("SELECT stops_name FROM stops WHERE `id` IN($value)");
        $sumfront[$i] = $rowsfront[0]["stops_name"];//массив с названиями остановок
        $i++;
    }
    //var_dump($sumfront);
    unset($value);
    
    //$fs_separated = implode(", ",$sumfront);//строка с названиями остановок
    //формируем перечень названий остановок по маршруту
    $i=0;
    foreach ($mback as $value){
        //var_dump($value);
        $rowsback = query("SELECT stops_name FROM stops WHERE `id` IN($value)");
        //var_dump($rowsfront);
        $sumback[$i] = $rowsback[0]["stops_name"];//массив с названиями остановок
        $i++;
    }
    unset($value);
    //$bs_separated = implode(", ",$sumback);//строка с названиями остановок
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
                                    foreach ($sumfront as $value){
                                       $idstops = query("SELECT `id` FROM `stops` WHERE `stops_name` = ?", $value);
                                       $stops = $idstops[0]["id"];
                                       //выводим html ссылки под названием остановки
                                       echo "<a href='javascript:update(5, $stops)' title='Показать остановку'>";
                                       //прописуем название остановки
                                       echo "$value";
                                       echo "</a>, ";
                                    }
                                    unset($value);
                                ?>
                       </td>
                       <td>
                                <?php 
                                   foreach ($sumback as $value){
                                       $idstops = query("SELECT `id` FROM `stops` WHERE `stops_name` = ?", $value);
                                       $stops = $idstops[0]["id"];
                                       //выводим html ссылки под названием остановки
                                       echo "<a href='javascript:update(5, $stops)' title='Показать остановку'>";
                                       //прописуем название остановки
                                       echo "$value";
                                        echo "</a>, ";
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