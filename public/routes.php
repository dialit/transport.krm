<?php

    // конфигурация
    require("../includes/config.php"); 

    // запрашивать базу данных transport для пользователя
    
    $rows = query("SELECT * FROM `transport` WHERE `id` = ?",$_GET["id"]);
    $k=$_GET["id"];
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

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    	<title>
    	   <?= $transport["type"] ?>.&nbsp; <?= $transport["n_marshr"] ?>
    	</title>
</head>
    
<body>
    <div id="content">
        <h1 class="tit18">
    		<?= $transport["type"] ?>&nbsp;№<?= $transport["n_marshr"] ?>
    	</h1>
    </div>
        <p>
    		<strong>Описание</strong>&nbsp;|&nbsp;
    		<a href="index.php" title="Вернуться на карту">Схема маршрута</a>&nbsp;|&nbsp;
    		<a href="kniga.php?id=<?=$k?>&c=" title="Отзывы о сервисе на маршруте...">Книга жалоб и предложений</a>&nbsp;|&nbsp;
    		<a href="news" title="Посмотреть все новости, связанные с данным маршрутом...">Новости по теме</a>
    	</p>
    <div class="tc_main">
    	<table class="under_line" cellspacing="0" cellpadding="3">
    		<tbody>
    		    <tr>
    		        <th>Стоимость проезда:</th>
    		        <td><?= $transport["cena"] ?></td>
    		    </tr>
    			<tr>
    			    <th>Интервал движения:</th>
    			    <td><?= $transport["int_dvij"] ?></td>
    			</tr>
    				<tr>
    				<th>Дни работы:</th>
    				<td><?= $transport["rej_raboty"] ?></td>
    			</tr>
    			<tr>
    				<th>Время работы:</th>
    				<td><?= $transport["vr_raboty"] ?></td>
    			</tr>
    			<tr>
    				<th>Конечные станции:</th>
    				<td><?= $transport["nach_kon"] ?></td>
    			</tr>
    				<tr>
    				<th>Путь следования:</th>
    				<td>
    				    <?php 
    				       foreach ($sumfront as $value){
    				           $idstops = query("SELECT `id` FROM `stops` WHERE `stops_name` = ?", $value);
    				           $stops = $idstops[0]["id"];
    				           //выводим html ссылки под названием остановки
    				           print "<a href='$stops' title='Показать остановку'>";
    				           //прописуем название остановки
    				           echo "$value,";
    				           print "</a> ";
    				       }
    				       unset($value);
                        ?>
                    </td>
    			</tr>
    				
    			<tr>
    				<th>Обратный путь следования:</th>
    				<td>
    				    <?php 
    				       foreach ($sumback as $value){
    				           $idstops = query("SELECT `id` FROM `stops` WHERE `stops_name` = ?", $value);
    				           $stops = $idstops[0]["id"];
    				           //выводим html ссылки под названием остановки
    				           print "<a href='$stops' title='Показать остановку'>";
    				           //прописуем название остановки
    				           echo "$value,";
    				           print "</a> ";
    				       }
    				       unset($value);
                        ?>
    				</td>
    			</tr>
    				
    			<tr>
    			    <th>Маршрут обслуживает: </th>
    			    <td><?= $transport["firma"] ?></td>
    			</tr>
    		</tbody>
    	</table>
    </div>
    <H2 ALIGN="center">Расписание</H2>
    <!-- Всталяем табличку с расписанием в фрейм -->
    	<iframe src="<?= $transport["rasp"] ?>" width="100%" height="70%"></iframe>
</body>
</html>