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
    unset($rows);
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
    
    $fs_separated = implode(", ",$sumfront);//строка с названиями остановок
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
    $bs_separated = implode(", ",$sumback);//строка с названиями остановок
?> 

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?= $transport["type"] ?>.&nbsp; <?= $transport["n_marshr"] ?></title>
</head>
<body>


		<div id="content">
		     <h1 class="tit18">
		        <?= $transport["type"] ?>&nbsp;№<?= $transport["n_marshr"] ?>
		     </h1>
		<p><strong>Описание</strong> |&nbsp;<a href="index.html" title="Вернуться на карту">Схема&nbsp;маршрута</a> |&nbsp;<a href="kniga.php?id=<?=$k?>&c=" title="Отзывы о сервисе на маршруте...">Книга жалоб и предложений</a> |&nbsp;<a href="/city_routes/193/news/" title="Посмотреть все новости, связанные с данным маршрутом...">Новости&nbsp;по&nbsp;теме</a></p>
<div class="tc_main">
	<table class="under_line" cellspacing="0" cellpadding="3">
				<tbody><tr><th>Стоимость проезда:</th><td><?= $transport["cena"] ?></td></tr>
				
				<tr><th>Интервал движения:</th><td><?= $transport["int_dvij"] ?></td></tr>
				
				<tr><th>Дни работы:</th><td><?= $transport["rej_raboty"] ?></td></tr>
				
				<tr><th>Время работы:</th><td><?= $transport["vr_raboty"] ?></td></tr>
				
				<tr><th>Конечные станции:</th><td><?= $transport["nach_kon"] ?></td></tr>
				
				<tr><th>Путь следования:</th><td><?= $fs_separated ?></td></tr>
				
				<tr><th>Обратный путь следования:</th><td><?= $bs_separated ?></td></tr>
				
				<tr><th>Маршрут обслуживает: </th><td><a href="/transporters/67/" title="Подробнее о перевозчике..."><?= $transport["firma"] ?></a></td></tr>
				</tbody></table>
		
</div>
</body>
</html>
<?= $transport["rospisanie"] ?>