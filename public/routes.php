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
    <h3><?= $transport["type"] ?>&nbsp;№<?= $transport["n_marshr"] ?></h3>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#panel1">Описание</a></li>
        <li><a data-toggle="tab" href="#panel2">Расписание</a></li>
        <li><a data-toggle="tab" href="#panel3">Отзывы и предложения</a></li>
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
	                                    echo "<a href='javascript:update(5, $id)' title='Показать остановку'>$names</a>, ";
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
	                                    echo "<a href='javascript:update(5, $id)' title='Показать остановку'>$names</a>, ";
                                    }
                                    unset($value);
                                ?>
                       </td>
                    </tr>
                </tbody>
            </table>
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <td>Маршрут обслуживает: <?= $transport["firma"] ?></td>
                            <td><i class="glyphicon glyphicon-map-marker" aria-hidden="true"></i>
                            <a href='javascript:draw_marshr(3,<?=$k?>)'>Показать маршрут на карте</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div><!-- End content Tab1 -->
        
        <div id="panel2" class="tab-pane fade">           
            <iframe src="<?= $transport["rasp"] ?>" frameborder="0" width="100%" height="600px"></iframe>
        </div><!-- End content Tab2 -->
        
        <div id="panel3" class="tab-pane fade">
           <div class="hide" id="respons"></div>
            <form method="POST" action="comment.php" enctype="multipart/form-data" name="form" onSubmit="return false">
                <div class="form-group">
                    <input type="hidden" class="form-control" name="<?= $transport["n_marshr"] ?>" value="<?= $transport["n_marshr"] ?>">
                    <label for="labelName">Имя:</label>
                    <input type="text" class="form-control" name="author" id="author" required>
                </div>
                <div class="form-group">
                    <label for="labelTema">Тема отзыва:</label>
                    <input type="text" class="form-control" name="tems" id="tema">
                </div>
                <div class="form-group">
                    <label for="labelText">Текст отзыва:</label>
                    <textarea name="comment" id="message" cols="30" rows="10" class="form-control"></textarea>
                </div>
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

        </div><!-- End content Tab4 -->
    </div>
</div>