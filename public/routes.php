<?php

    // конфигурация
    require("../includes/config.php"); 

    // запрашивать базу данных transport для пользователя
    
    $rows = query("SELECT * FROM `transport` WHERE `id` = ?",$_GET["id"]);
    foreach ($rows as $row){
        $front = $row["front"];//список id остановок по маршруту
        $back = $row["back"];//список id остановок по маршруту в обратном направлении
    }
    //var_dump($front);
    //формируем перечень названий остановок по маршруту
    $rowsfront = query("SELECT stops_name FROM stops WHERE `id` IN($front)");
    foreach ($rowsfront as $row){
        $sumfront[] = $row["stops_name"];//массив с названиями остановок
    }
    $fs_separated = implode(", ",$sumfront);//строка с названиями остановок
    //формируем перечень названий остановок по маршруту
    $rowsback = query("SELECT stops_name FROM stops WHERE `id` IN($back)");
    foreach ($rowsback as $row){
        $sumback[] = $row["stops_name"];//массив с названиями остановок
    }
    $bs_separated = implode(", ",$sumback);//строка с названиями остановок
    //var_dump($bs_separated);
    $transport = $rows[0];

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
		<p><strong>Описание</strong> |&nbsp;<a href="index.html" title="Вернуться на карту">Схема&nbsp;маршрута</a> |&nbsp;<a href="kniga.php?c=&tip=<?=$transport["type"]?>&nomer=<?=$transport["n_marshr"]?>" title="Отзывы о сервисе на маршруте...">Книга жалоб и предложений</a> |&nbsp;<a href="/city_routes/193/news/" title="Посмотреть все новости, связанные с данным маршрутом...">Новости&nbsp;по&nbsp;теме</a></p>
<div class="tc_main">
	<table class="under_line" cellspacing="0" cellpadding="3">
				<tbody><tr><th>Стоимость проезда:</th><td><?= $transport["cena"] ?></td></tr>
				
				<tr><th>Интервал движения:</th><td><?= $transport["int_dvij"] ?></td></tr>
				
				<tr><th>Дни работы:</th><td><?= $transport["rej_raboty"] ?></td></tr>
				
				<tr><th>Время работы:</th><td><?= $transport["vr_raboty"] ?></td></tr>
				
				<tr><th>Конечные станции:</th><td><?= $transport["nach_kon"] ?></td></tr>
				
				<tr><th>Путь следования:</th><td><a href="/city_routes/ks/118/" title="Подробнее о конечной..."><strong>Южный вокзал</strong></a> - <a href="/streets/133/" title="Подробнее об улице...">ул.&nbsp;Евгения Котляра</a>, <a href="/streets/179/" title="Подробнее об улице...">ул.&nbsp;Полтавский Шлях</a>, <a href="/streets/40/" title="Подробнее об улице...">пл.&nbsp;Сергиевская</a>, <a href="/streets/41/" title="Подробнее об улице...">Павловская&nbsp;пл.</a>, <a href="/streets/39/" title="Подробнее об улице...">пл.&nbsp;Конституции</a>, <a href="/streets/56/" title="Подробнее об улице...">пр.&nbsp;Московский</a>, <a href="/streets/36/" title="Подробнее об улице...">пл.&nbsp;Защитников Украины</a>, <a href="/streets/120/" title="Подробнее об улице...">ул.&nbsp;Молочная</a>, <a href="/streets/176/" title="Подробнее об улице...">ул.&nbsp;Плехановская</a>, <a href="/streets/159/" title="Подробнее об улице...">ул.&nbsp;Морозова</a>, <a href="/streets/49/" title="Подробнее об улице...">пр.&nbsp;Героев Сталинграда</a> - <a href="/city_routes/ks/105/" title="Подробнее о конечной..."><strong>Ул. Одесская (Просп. Гагарина)</strong></a></td></tr>

				<tr><th>путь следования:</th><td><?= $fs_separated ?></td></tr>
				
				<tr><th>обратный путь следования:</th><td><?= $bs_separated ?></td></tr>
				
				<tr><th>Маршрут обслуживает: </th><td><a href="/transporters/67/" title="Подробнее о перевозчике...">Салтовское трамвайное депо</a></td></tr>
				</tbody></table>
	
</div>
</body>
</html>
